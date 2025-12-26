<?php

namespace App\DataTables;

use App\Models\Expense;
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

class ExpenseDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Expense> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('expense_number', function ($query) {
                return ' <strong>'.$query->expense_number.'</strong>';
            })
            ->addColumn('type', function ($query) {
                return '<span class="badge badge-soft-danger">'.$query->type->name.'</span>';
            })
            ->addColumn('date', function ($query) {
                return $query->expense_date->format('M d, Y');

            })->addColumn('payment_method', function ($invoice) {
                return '<span class="badge badge-soft-info">'.ucfirst(str_replace('_', ' ', $invoice->payment_method)).'</span>';
            }) ->addColumn('amount', function ($query) {
                return  '$'.number_format($query->amount, 2);

            })->addColumn('receipt', function ($query) {
                if($query->receipt){
                    return '<a href="'.$query->receipt_url.'" target="_blank" class="btn btn-sm btn-soft-primary">
                                                <i class="ri-file-text-line"></i> View
                                            </a>';
                } else {
                    return '<span class="text-muted">No receipt</span>';
                }

            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['expense_number', 'type', 'date','payment_method','amount','receipt', 'action']);
    }

    public function query(Expense $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', $parentId)
        ->with('type');


        // Search filter
        if ($request->has('type') && $request->type != '') {
            $query->where('type_id', $request->type);
        }

        // Date range filter
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('expense_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }

        $query = $query->latest();
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('expense-table')
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
            Column::make('expense_number')->title('Expense #'),
            Column::make('title')->title('Title'),
            Column::make('type.name')->title('Type'),
            Column::make('amount')->title('Amount'),
            Column::make('date')->title('Date'),
            Column::make('payment_method')->title('Payment Method'),
            Column::make('receipt')->title('Receipt'),
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
        return 'Expense_' . date('YmdHis');
    }
}
