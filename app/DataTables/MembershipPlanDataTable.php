<?php

namespace App\DataTables;

use App\Models\MembershipPlan;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MembershipPlanDataTable extends DataTable
{
    use DataTableConfigTrait;

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<MembershipPlan>  $query  Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('duration', function ($query) {
                return $query->duration_value.' '.ucfirst($query->duration_type);
            })
            ->addColumn('active', function ($query) {
                if ($query->is_active) {
                    return '<span class="badge bg-success">Active</span>';
                } else {
                    return '<span class="badge bg-secondary">Inactive</span>';
                }
            })
            ->addColumn('personal_training', function ($query) {
                if ($query->personal_training) {
                    return '<span class="badge bg-info">Yes</span>';
                } else {
                    return '<span class="badge bg-secondary">No</span>';
                }
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['duration', 'active', 'personal_training', 'action']);
    }

    public function query(MembershipPlan $model)
    {
        $parentId = parentId();

        return $model->newQuery()->where('parent_id', $parentId)->latest();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('membership-table')
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
            Column::make('price')->title('Price'),
            Column::make('duration')->title('Duration'),
            Column::make('active')->title('Active'),
            Column::make('personal_training')->title('Personal Training'),
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
        return 'MembershipPlan_'.date('YmdHis');
    }
}
