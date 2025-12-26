<?php

namespace App\DataTables;

use App\Models\Invoice;
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

class InvoiceDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Invoice> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('invoice', function ($query) {
                return ' <a href="'.route('invoices.show', $query->id).'" class="fw-medium link-primary">{{ $invoice->invoice_number }}</a>';
            })
            ->addColumn('member', function ($invoice) {
                if ($invoice->member) {
                    $html = '<div class="d-flex align-items-center">';

                    // Avatar
                    if ($invoice->member->avatar) {
                        $html .= '<div class="flex-shrink-0 me-2">';
                        $html .= '<img src="' . URL::asset('images/' . $invoice->member->avatar) . '" alt="" class="avatar-xs rounded-circle">';
                        $html .= '</div>';
                    } else {
                        $html .= '<div class="avatar-xs me-2">';
                        $html .= '<span class="avatar-title rounded-circle bg-soft-primary text-primary">';
                        $html .= substr($invoice->member->name, 0, 1);
                        $html .= '</span>';
                        $html .= '</div>';
                    }

                    // Name
                    $html .= '<div class="flex-grow-1">';
                    $html .= e($invoice->member->name); // Use e() to escape HTML
                    $html .= '</div>';

                    $html .= '</div>';

                    return $html;
                } else {
                    return '<span class="text-muted">Unknown Member</span>';
                }
            })
            ->addColumn('invoice_date', function ($query) {
                return $query->invoice_date->format('M d, Y');

            })->addColumn('due_date', function ($invoice) {
                $textClass = $invoice->isOverdue() ? 'text-danger fw-bold' : '';
                return '<span class="' . $textClass . '">' . $invoice->due_date->format('M d, Y') . '</span>';
            }) ->addColumn('total_amount', function ($query) {
                return  '$'.number_format($query->total_amount, 2);

            })->addColumn('paid_amount', function ($query) {
                return  '$'.number_format($query->paid_amount, 2);

            })->addColumn('status', function ($query) {
                if ($query->status == 'paid'){
                    return '<span class="badge badge-soft-success">'.ucfirst(str_replace('_', ' ', $query->status)).'</span>';
                } elseif ($query->status == 'partially_paid'){
                    return '<span class="badge badge-soft-warning">'.ucfirst(str_replace('_', ' ', $query->status)).'</span>';
                } else {
                    return '<span class="badge badge-soft-danger">'.ucfirst(str_replace('_', ' ', $query->status)).'</span>';
                }

            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['invoice', 'member', 'invoice_date','due_date','total_amount','paid_amount','status', 'action']);
    }

    public function query(Invoice $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', $parentId)
        ->with(['member', 'items']);


        // Search filter
        if ($request->has('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('member', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }
        $query = $query->latest('invoice_date');
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('invoice-table')
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
            Column::make('invoice')->title('Invoice #'),
            Column::make('member.name')->title('Member'),
            Column::make('invoice_date')->title('Date'),
            Column::make('due_date')->title('Due Date'),
            Column::make('status')->title('Status'),
            Column::make('total_amount')->title('Amount'),
            Column::make('paid_amount')->title('Paid'),
//            Column::make('formatted_hourly_rate')->title('Hourly Rate'),
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
        return 'Invoice_' . date('YmdHis');
    }
}
