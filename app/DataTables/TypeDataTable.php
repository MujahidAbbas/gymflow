<?php

namespace App\DataTables;

use App\Models\Invoice;
use App\Models\Type;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TypeDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Type> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('category', function ($query) {
                if ($query->category == 'income'){
                    return '<span class="badge badge-soft-success">'.ucfirst($query->category).'</span>';
                } else {
                    return '<span class="badge badge-soft-danger">'.ucfirst($query->category).'</span>';
                }
            })->addColumn('status', function ($query) {
                if ($query->status == 'is_active'){
                    return '<span class="badge badge-soft-success">Active</span>';
                }  else {
                    return '<span class="badge badge-soft-secondary">Inactive</span>';
                }

            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['category', 'status', 'action']);
    }

    public function query(Type $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', $parentId);


        // Search filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        $query = $query->latest();
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('type-table')
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
            Column::make('category')->title('Category'),
            Column::make('description')->title('Description'),
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
        return 'Type_' . date('YmdHis');
    }
}
