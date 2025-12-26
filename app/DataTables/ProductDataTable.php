<?php

namespace App\DataTables;

use App\Models\Product;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    use DataTableConfigTrait;

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<Product>  $query  Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('category', function ($query) {
                return '<span class="badge bg-info">'.$query->category->name.'</span>';
            })
            ->editColumn('price', function ($query) {
                return '$'.number_format($query->price, 2);
            })
            ->addColumn('stock', function ($query) {
                if ($query->isLowStock()) {
                    return '<span class="badge bg-warning text-dark">'.$query->stock_quantity.'</span>';
                } elseif ($query->stock_quantity == 0) {
                    return '<span class="badge bg-danger">Out of Stock</span>';
                } else {
                    return '<span class="badge bg-success">'.$query->stock_quantity.'</span>';
                }
            })
            ->addColumn('status', function ($query) {
                if ($query->active) {
                    return '<span class="badge bg-success">Active</span>';
                } else {
                    return '<span class="badge bg-secondary">Inactive</span>';
                }
            })
            ->addColumn('created', function ($query) {
                return $query->created_at->format('M d, Y');
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['category', 'price', 'stock', 'status', 'action']);
    }

    public function query(Product $model)
    {
        $request = $this->request;
        $query = $model->newQuery()->where('parent_id', parentId())
            ->with('category');

        if ($request->has('search_value') && $request->search_value != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search_value.'%')
                    ->orWhere('sku', 'like', '%'.$request->search_value.'%');
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Stock filter
        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status == 'low') {
                $query->lowStock();
            } elseif ($request->stock_status == 'out') {
                $query->where('stock_quantity', 0);
            }
        }

        return $query->latest('products.created_at');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('product-table')
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
            Column::make('category.name')->title('Category'),
            Column::make('price')->title('Price'),
            Column::make('stock')->title('Stock'),
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
        return 'Product_'.date('YmdHis');
    }
}
