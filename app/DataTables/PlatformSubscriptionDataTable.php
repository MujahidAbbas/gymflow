<?php

namespace App\DataTables;

use App\Models\PlatformSubscriptionTier;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PlatformSubscriptionDataTable extends DataTable
{
    use DataTableConfigTrait;

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<PlatformSubscription>  $query  Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('name', function ($query) {
                $business = '<strong>'.$query->name.'</strong>';
                if ($query->is_featured) {
                    $business .= '<span class="badge bg-warning ms-1">Featured</span>';
                }
                if ($query->description) {
                    $business .= '<br><small class="text-muted">'.Str::limit($query->description, 50).'</small>';
                }

                return $business;
            })
            ->editColumn('formatted_price', function ($query) {
                $owner = '<strong>'.$query->formatted_price.'</strong>'.$query->interval_label;
                if ($query->trial_days > 0) {
                    $owner .= '<br><small class="text-success">'.$query->trial_days.' days trial</small>';
                }

                return $owner;
            })
            ->addColumn('interval', function ($query) {
                return '<span class="badge bg-info">'.ucfirst($query->interval).'</span>';
            })
            ->addColumn('limits', function ($query) {
                $subscription = '<strong>Members:</strong>';
                if ($query->hasUnlimitedMembers()) {
                    $subscription .= 'Unlimited';
                } else {
                    $subscription .= number_format($query->max_members_per_tenant);
                }
                $subscription .= '<br><strong>Trainers:</strong>';
                if ($query->hasUnlimitedTrainers()) {
                    $subscription .= 'Unlimited';
                } else {
                    $subscription .= number_format($query->max_trainers_per_tenant);
                }

                return $subscription;
            })
            ->addColumn('status', function ($query) {
                if ($query->is_active) {
                    return '<span class="badge bg-success">Active</span>';
                } else {
                    return '<span class="badge bg-secondary">Inactive</span>';
                }
            })
            ->addColumn('customer', function ($query) {
                return '<span class="badge bg-primary">'.$query->tenants_count.'</span>'.'customers';
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['name', 'formatted_price', 'interval', 'limits', 'status', 'customer', 'action']);
    }

    public function query(PlatformSubscriptionTier $model)
    {
        return $model->newQuery()->withCount('tenants');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('subscription-table')
            ->addTableClass('datatables-basic table table-striped')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(false)
            ->orderBy(1, 'asc')
            ->parameters($this->getDataTableParameters(7));
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', 'No'),
            Column::make('name')->title('Tier Name'),
            Column::make('formatted_price')->title('Price'),
            Column::make('interval')->title('Interval'),
            Column::make('limits')->title('Limits'),
            Column::make('customer')->title('Customers'),
            Column::make('status')->title('Status'),
            Column::computed('action', 'Action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PlatformSubscription_'.date('YmdHis');
    }
}
