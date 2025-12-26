<?php

namespace App\DataTables;

use App\Models\Member;
use App\Models\Trainer;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TrainerDataTable extends DataTable
{
    use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Trainer> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('specializations', function ($trainer) {
                if ($trainer->specializations && count($trainer->specializations) > 0) {
                    $html = '';
                    foreach(array_slice($trainer->specializations, 0, 2) as $spec) {
                        $html .= '<span class="badge badge-soft-info">' . e($spec) . '</span> ';
                    }
                    if (count($trainer->specializations) > 2) {
                        $html .= '<span class="badge badge-soft-secondary">+' . (count($trainer->specializations) - 2) . '</span>';
                    }
                    return $html;
                } else {
                    return '<span class="text-muted">None</span>';
                }
            })
            ->addColumn('status', function ($query) {
                if ($query->status) {
                    return '<span class="badge badge-soft-success">'.ucfirst($query->status).'</span>';
                }  else {
                    return '<span class="badge badge-soft-secondary">'.ucfirst($query->status).'</span>';
                }
            })
            ->addColumn('years_of_experience', function ($query) {
               return $query->years_of_experienc.' '.'years';

            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['specializations', 'status', 'years_of_experience', 'action']);
    }

    public function query(Member $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', $parentId);;


        // Search filter
        if ($request->has('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
//                    ->orWhere('trainer_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
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
            ->setTableId('trainer-table')
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
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Phone'),
            Column::make('specializations')->title('Specializations'),
            Column::make('status')->title('Status'),
            Column::make('years_of_experience')->title('Experience'),
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
        return 'Trainer_' . date('YmdHis');
    }
}
