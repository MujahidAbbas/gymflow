<?php

namespace App\DataTables;

use App\Models\Invoice;
use App\Models\SupportTicket;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SupportTicketDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<SupportTicket> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('ticket_number', function ($query) {
                return '<strong>'.$query->ticket_number.'</strong>';
            })
            ->addColumn('subject', function ($invoice) {
                return Str::limit($invoice->subject, 40);
            })
            ->addColumn('member', function ($query) {
                return  $query->member->name ?? 'N/A';

            })->addColumn('priority', function ($query) {
                if ($query->priority == 'urgent'){
                    return ' <span class="badge bg-danger">Urgent</span>';
                } elseif ($query->priority == 'high'){
                    return '<span class="badge bg-warning text-dark">High</span>';
                } elseif ($query->priority == 'medium'){
                    return '<span class="badge bg-info">Medium</span>';
                } else {
                    return '<span class="badge bg-secondary">Low</span>';
                }
            }) ->addColumn('status', function ($query) {
                if ($query->status == 'open'){
                    return ' <span class="badge bg-primary">Open</span>';
                } elseif ($query->status == 'in_progress'){
                    return '<span class="badge bg-warning text-dark">In Progress</span>';
                } elseif ($query->status == 'resolved'){
                    return '<span class="badge bg-success">Resolved</span>';
                } else {
                    return ' <span class="badge bg-secondary">Closed</span>';
                }

            })->addColumn('created', function ($query) {
                return  $query->created_at->format('M d, Y');

            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['ticket_number', 'subject', 'priority','status','created', 'action']);
    }

    public function query(SupportTicket $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', parentId())
        ->with(['member', 'createdBy', 'assignedTo']);



        // Search filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }
        $query = $query->latest();
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('ticket-table')
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
            Column::make('ticket_number')->title('Ticket #'),
            Column::make('member.name')->title('Member'),
            Column::make('subject')->title('Subject'),
            Column::make('priority')->title('Priority'),
            Column::make('status')->title('Status'),
            Column::make('created')->title('Created'),
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
        return 'SupportTicket_' . date('YmdHis');
    }
}
