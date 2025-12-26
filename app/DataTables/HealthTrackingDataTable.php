<?php

namespace App\DataTables;

use App\Models\Health;
use App\Models\HealthTracking;
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

class HealthTrackingDataTable extends DataTable
{use DataTableConfigTrait;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<HealthTracking> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('measurement_date', function ($query) {
                return $query->measurement_date->format('M d, Y');
            })
            ->addColumn('member', function ($query) {
                return @$query->member->name ? $query->member->name : "N/A";
            })
            ->addColumn('height', function ($query) {
                return $query->getMeasurement('height') ?? '-';

            })->addColumn('weight', function ($query) {
                return $query->getMeasurement('weight') ?? '-';

            }) ->addColumn('waist', function ($query) {
                return $query->getMeasurement('waist') ?? '-';

            })->addColumn('chest', function ($query) {
                return $query->getMeasurement('chest') ?? '-';

            })->addColumn('body_fat', function ($query) {
                return $query->getMeasurement('body_fat') ?? '-';

            })->addColumn('bmi', function ($query) {
                if ($query->bmi_category == 'Normal'){
                    return '<span class="badge bg-success">'.$query->bmi.' .'-'.'.$query->bmi_category.'</span>';
                } elseif ($query->bmi_category == 'Overweight'){
                    return '<span class="badge bg-warning text-dark">'.$query->bmi.' .'-'.'.$query->bmi_category.'</span>';
                } elseif ($query->bmi_category == 'Obese'){
                    return '<span class="badge bg-danger">'.$query->bmi.' .'-'.'.$query->bmi_category.'</span>';
                } else {
                    return '<span class="badge bg-info">'.$query->bmi.' .'-'.'.$query->bmi_category.'</span>';
                }

            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['measurement_date', 'member', 'height','weight','waist','chest','body_fat','bmi', 'action']);
    }

    public function query(Health $model)
    {
        $parentId = parentId();

        $request = $this->request;

        $query =  $model->newQuery()->where('parent_id', $parentId)
            ->with(['member']);


        // Member filter
        if ($request->has('member') && $request->member != '') {
            $query->where('member_id', $request->member);
        }

        // Status filter
        $query = $query->latest();
        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('healths-table')
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
            Column::make('measurement_date')->title('Date'),
            Column::make('member.name')->title('Member'),
            Column::make('health')->title('Height (cm)'),
            Column::make('weight')->title('Weight (kg)'),
            Column::make('bmi')->title('BMI'),
            Column::make('waist')->title('Waist (cm)'),
            Column::make('chest')->title('Chest (cm)'),
            Column::make('body_fat')->title('>Body Fat (%)'),
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
        return 'HealthTracking_' . date('YmdHis');
    }
}
