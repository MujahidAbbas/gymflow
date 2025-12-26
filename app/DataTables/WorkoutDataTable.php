<?php

namespace App\DataTables;

use App\Models\Workout;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class WorkoutDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Workout> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('workout_id', function ($query) {
                return '<strong>'.$query->workout_id.'</strong>';
            })
            ->addColumn('member', function ($query) {
                return @$query->member->name ? $query->member->name : "N/A";
            })
            ->addColumn('trainer', function ($query) {
                return @$query->workout_date ? $query->workout_date->format('M d, Y') : "Template";

            })->addColumn('date', function ($query) {
                return ' <span class="badge bg-info">'.$query->enrolled_count.'.'/'.'.$query->max_capacity.'</span>';

            }) ->addColumn('activity', function ($query) {
                return ' <span class="badge bg-primary">'.$query->activities->count().' exercises</span>';

            })->addColumn('completion', function ($query) {
               $completion = '<div class="progress-bar" role="progressbar" style="width: '.$query->completion_percentage.'%"  aria-valuenow="'.$query->completion_percentage.'"  aria-valuemin="0" aria-valuemax="100">'.$query->completion_percentage.'%</div>';

            }) ->addColumn('status', function ($query) {
                if ($query->status == 'active'){
                    return '<span class="badge badge-soft-success">'.ucfirst($query->status).'</span>';
                } elseif ($query->status == 'inactive'){
                    return '<span class="badge badge-soft-info">'.ucfirst($query->status).'</span>';
                } else {
                    return '<span class="badge   badge-soft-danger">'.ucfirst($query->status).'</span>';
                }

            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['workout_id', 'member', 'trainer','date','activity','completion','status', 'action']);
    }

    public function query(Workout $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', $parentId)
            ->with(['member', 'trainer', 'activities']);




        // Member filter
        if ($request->has('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('workout_id', 'like', "%{$search}%");
            });
        }

        // Member filter
        if ($request->has('member') && $request->member != '') {
            $query->where('member_id', $request->member);
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
            ->setTableId('workout-table')
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
            Column::make('workout_id')->title('Workout Id'),
            Column::make('name')->title('Name'),
            Column::make('member.name')->title('Member'),
            Column::make('trainer')->title('Trainer'),
            Column::make('date')->title('Date'),
            Column::make('activity')->title('Activities'),
            Column::make('completion')->title('Completion'),
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
        return 'Workout_' . date('YmdHis');
    }
}
