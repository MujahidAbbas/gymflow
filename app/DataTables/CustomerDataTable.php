<?php

namespace App\DataTables;

use App\Models\Tenant;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerDataTable extends DataTable
{
    use DataTableConfigTrait;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Customer> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('business_name', function ($query) {
                $business = '<strong>'.$query->business_name.'</strong>';
                if ($query->subdomain) {
                    $business .= '<br><small class="text-muted">'.$query->subdomain.'</small>';
                }
                return $business;
            })
            ->editColumn('owner', function ($query) {
                $owner = '<strong>'.$query->owner->name.'</strong>';
                if ($query->owner->email) {
                    $owner .= '<br><small class="text-muted">'.$query->owner->email.'</small>';
                }
                return $owner;
            })
            ->addColumn('status', function ($query) {
                if ($query->status == "active") {
                    return '<span class="badge bg-success">Active</span>';
                } elseif ($query->status === 'suspended') {
                    return '<span class="badge bg-danger">Suspended</span>';
                } else {
                    return '<span class="badge bg-secondary">Inactive</span>';
                }
            })
            ->addColumn('subscription', function ($query) {
                if ($query->isOnTrial()) {
                    $subscription = '<span class="badge bg-info">Trial</span><br><small>'.$query->trial_ends_at->diffForHumans().'</small>';
                } else {
                    $subscription = '<span class="badge bg-success">Paid</span>';
                }
                return $subscription;
            })
            ->addColumn('created', function ($query) {
                return $query->created_at->format('M d, Y');
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['business_name', 'owner', 'status', 'subscription', 'created', 'action']);
    }

    public function query(Tenant $model)
    {
        $request = $this->request;
        $query = $model->newQuery()->with('owner');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhereHas('owner', function ($ownerQuery) use ($search) {
                        $ownerQuery->where('email', 'like', "%{$search}%");
                    });
            });
        }

        return $query->latest('tenants.created_at');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('customer-table')
            ->addTableClass('datatables-basic table table-striped')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(false)
            ->orderBy(1, 'asc')
            ->parameters($this->getDataTableParameters(6));
    }

    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', 'No'),
            Column::make('business_name')->title('Business Name'),
            Column::make('owner')->name('owner.name')->title('Owner'),
            Column::make('status')->title('Status'),
            Column::make('subscription')->title('Subscription'),
            Column::make('created')
                ->name('tenants.created_at')
                ->title('Created')
                ->orderable(true)
                ->searchable(false),
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
        return 'Customer_' . date('YmdHis');
    }
}
