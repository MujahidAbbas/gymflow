<?php

namespace App\DataTables;

use App\Models\Category;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    use DataTableConfigTrait;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Category> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('color', function ($query) {
                $color = e($query->color);
                return '<div style="background-color: '.$color.'; width: 50px; height: 25px;"></div>';
            })
            ->addColumn('description', function ($query) {
                return Str::limit($query->description, 60);
            })
            ->addColumn('classes', function ($query) {
                return '<span class="badge bg-info">'.$query->gym_classes_count.' classes</span>';
            })
            ->addColumn('is_active', function ($query) {
                if ($query->is_active) {
                    return '<span class="badge bg-success">Active</span>';
                } else {
                    return '<span class="badge bg-secondary">Inactive</span>';
                }
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['color', 'description', 'classes', 'is_active', 'action']);
    }

    public function query(Category $model)
    {
        $parentId = parentId();
        return $model->newQuery()->where('parent_id', $parentId)
            ->withCount('gymClasses')
            ->latest();
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('category-table')
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
            Column::make('color')->title('Color'),
            Column::make('name')->title('Name'),
            Column::make('description')->title('Description'),
            Column::make('classes')->title('Classes'),
            Column::make('is_active')->title('Status'),
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
        return 'Category_' . date('YmdHis');
    }
}
