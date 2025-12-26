<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Models\AttendanceReport;
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

class AttendanceReportDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<AttendanceReport> $query Results from query() method.
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
                return $query->member->name;
            })
            ->addColumn('check_in', function ($query) {
                return $query->check_in_time->format('h:i A');

            }) ->addColumn('check_out', function ($query) {
                return $query->check_out_time ? $query->check_out_time->format('h:i A') : '-';

            })
            ->addColumn('duration', function ($query) {
                return $query->duration_minutes;
            })
            ->rawColumns(['specializations', 'status', 'years_of_experience', 'action']);
    }

    public function query(Attendance $model)
    {
        $parentId = parentId();

        $request = $this->request;
        $parentId = parentId();

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));


        $query =  $model->newQuery()->whereBetween('date', [$startDate, $endDate])
            ->with('member');

        $query = $query->latest('attendances.created_at');
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
            Column::make('member.name')->title('Member'),
            Column::make('check_in')->title('Check In'),
            Column::make('check_out')->title('Check Out'),
            Column::make('duration')->title('Duration'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AttendanceReport_' . date('YmdHis');
    }
}
