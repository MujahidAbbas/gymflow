<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Models\Member;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AttendanceDataTable extends DataTable
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
            ->editColumn('date', function ($query) {
                return $query->date->format('M d, Y');
            })
            ->addColumn('member', function ($query) {
                return '<strong>'.$query->member->name.'</strong><small class="text-muted">'.$query->member->member_id.'</small>';
            })
            ->addColumn('check_in', function ($query) {
                return $query->check_in_time->format('h:i A');

            })->addColumn('check_out', function ($query) {
               if ($query->check_out_time){
                   return $query->check_out_time->format('h:i A');
               } else {
                   return ' <span class="badge bg-warning text-dark">In Gym</span>';
               }

            }) ->addColumn('duration', function ($query) {
                return '<span class="badge bg-info">'.$query->formatted_duration.'</span>';

            })->addColumn('status', function ($query) {
                if ($query->isCheckedOut()){
                    return ' <span class="badge bg-success">
                                                <i class="ri-check-line"></i> Completed
                                            </span>';
                } else {
                    return ' <span class="badge bg-primary">
                                                <i class="ri-time-line"></i> Active
                                            </span>';
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
            ->rawColumns(['member', 'status', 'check_in','check_out','duration','date', 'action']);
    }

    public function query(Attendance $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', $parentId)
        ->with('member');


        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        } else {
            // Default to today
            $query->today();
        }

        // Member filter
        if ($request->has('member') && $request->member != '') {
            $query->where('member_id', $request->member);
        }

        // Plan filter
        if ($request->has('plan') && $request->plan != '') {
            $query->where('membership_plan_id', $request->plan);
        }
        $query = $query->latest('check_in_time');
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('attendance-table')
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
            Column::make('date')->title('Date'),
            Column::make('member.name')->title('Member'),
            Column::make('check_in')->title('Check In'),
            Column::make('check_out')->title('Check Out'),
            Column::make('duration')->title('Duration'),
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
        return 'Attendance_' . date('YmdHis');
    }
}
