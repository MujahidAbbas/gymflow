<?php

namespace App\DataTables;

use App\Models\Member;
use App\Models\MembershipPlan;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MemberDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Member> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('membership_plan', function ($query) {
                if ($query->membershipPlan) {
                    return '<span class="badge bg-info">'.$query->membershipPlan->name.'</span>';
                } else {
                    return ' <span class="badge bg-secondary">No Plan</span>';
                }
            })
            ->addColumn('status', function ($query) {
                if ($query->status) {
                    return '<span class="badge bg-success">'.ucfirst($query->status).'</span>';
                } elseif($query->status == 'inactive') {
                    return '<span class="badge bg-secondary">'.ucfirst($query->status).'</span>';
                }elseif($query->status == 'expired') {
                    return '<span class="badge bg-danger">'.ucfirst($query->status).'</span>';
                } else {
                    return '<span class="badge bg-warning text-dark">'.ucfirst($query->status).'</span>';
                }
            })
            ->addColumn('expiry_date', function ($query) {
               if ($query->membership_end_date){
                   if ($query->isExpired()){
                       return '<span class="badge bg-danger ms-1">Expired</span>';
                   }
                   return $query->membership_end_date->format('M d, Y');
               } else {
                   return ' <span class="text-muted">Lifetime</span>';
               }

            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['membership_plan', 'status', 'expiry_date', 'action']);
    }

    public function query(Member $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->with('membershipPlan')->where('parent_id', $parentId);


        if ($request->has('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Plan filter
        if ($request->has('plan') && $request->plan != '') {
            $query->where('membership_plan_id', $request->plan);
        }
        $query = $query->latest();
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('member-table')
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
            Column::make('name')->title('Name'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Phone'),
            Column::make('membership_plan')->title('Plan'),
            Column::make('status')->title('Status'),
            Column::make('expiry_date')->title('Expiry Date'),
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
        return 'Member_' . date('YmdHis');
    }
}
