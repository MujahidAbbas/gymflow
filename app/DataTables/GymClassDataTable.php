<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Models\GymClass;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class GymClassDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Attendance> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('class_id', function ($query) {
                return '<strong>'.$query->class_id.'</strong>';
            })
            ->addColumn('category', function ($query) {
                if ($query->category){
                    return '<span class="badge" style="background-color: '.$query->category->color.'">
                                                '.$query->category->name.'
                                            </span>';
                } else {
                    return ' <span class="text-muted">No Category</span>';
                }
            })
            ->addColumn('difficulty', function ($query) {
                if ($query->difficulty_level == 'beginner'){
                    return '<span class="badge bg-success">'.ucfirst($query->difficulty_level).'</span>';
                } elseif ($query->difficulty_level == 'intermediate'){
                    return '<span class="badge bg-warning text-dark">'.ucfirst($query->difficulty_level).'</span>';
                } else {
                    return '<span class="badge  bg-danger">'.ucfirst($query->difficulty_level).'</span>';
                }

            })->addColumn('capacity', function ($query) {
                return ' <span class="badge bg-info">'.$query->enrolled_count.'.'/'.'.$query->max_capacity.'</span>';

            }) ->addColumn('duration', function ($query) {
                return $query->duration_minutes. 'min';

            })->addColumn('schedule', function ($query) {
                if ($query->schedules->count() > 0){
                    return ' <span class="badge bg-primary">'.$query->schedules->count().' schedules</span>';
                } else {
                    return '<span class="text-muted">No schedules</span>';
                }

            }) ->addColumn('status', function ($query) {
                if ($query->status == 'active'){
                    return '<span class="badge bg-success">'.ucfirst($query->status).'</span>';
                } elseif ($query->status == 'inactive'){
                    return '<span class="badge bg-secondary">'.ucfirst($query->status).'</span>';
                } else {
                    return '<span class="badge  bg-danger">'.ucfirst($query->status).'</span>';
                }

            })
            ->addColumn('action', function ($query) {
                if(!$query->isCheckedOut()){
                    return '<a href="javascript:void(0);" class="link-success" onclick="checkOut('.$query->id.')">
                                                    <i class="ri-logout-circle-line"></i> Check Out
                                                </a>';
                } else {
                    return '<a href="javascript:void(0);" class="link-danger" onclick="deleteAttendance('.$query->id.')">
                                                <i class="ri-delete-bin-line"></i>
                                            </a>';
                }
            })
            ->rawColumns(['class_id', 'category', 'difficulty','capacity','duration','schedule','status', 'action']);
    }

    public function query(GymClass $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', $parentId)
        ->with(['category', 'schedules.trainer']);




        // Member filter
        if ($request->has('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('class_id', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        $query = $query->latest();
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('gym-table')
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
            Column::make('class_id')->title('Class ID'),
            Column::make('name')->title('Name'),
            Column::make('category.name')->title('Category'),
            Column::make('difficulty')->title('Difficulty'),
            Column::make('capacity')->title('Capacity'),
            Column::make('duration')->title('Duration'),
            Column::make('schedule')->title('Schedules'),
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
        return 'GymClass_' . date('YmdHis');
    }
}
