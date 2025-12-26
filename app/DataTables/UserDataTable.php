<?php

namespace App\DataTables;

use App\Models\User;
use App\Traits\DataTableConfigTrait;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    use DataTableConfigTrait;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('name', function ($query) {
                $html = '<div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-2">
                            <div class="avatar-xs">
                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">';

                $html .= substr($query->name, 0, 1);

                $html .= '</div>
                        </div>
                    </div>
                    <div class="flex-grow-1">';

                $html .= $query->name;

                if ($query->id == auth()->id()) {
                    $html .= '<span class="badge bg-info ms-1">You</span>';
                }

                $html .= '</div>
                    </div>';

                return $html;
            })
            ->editColumn('roles', function ($query) {
                if (!$query->roles || $query->roles->isEmpty()) {
                    return '<span class="badge bg-secondary">No Role</span>';
                }

                $badges = '';
                foreach ($query->roles as $role) {
                    $badges .= '<span class="badge bg-success me-1">' . ucfirst($role->name) . '</span>';
                }
                return $badges;
            })
            ->addColumn('created', function ($query) {
                return $query->created_at->format('M d, Y');
            })
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['name', 'roles', 'created', 'action']);
    }

    public function query(User $model)
    {
        $request = $this->request;
        $parentId = parentId();
        $query = $model->newQuery()->with('roles')->where(function ($q) use ($parentId) {
            $q->where('parent_id', $parentId)
                ->orWhere('id', $parentId);
        });

        if ($request->has('search_value')) {
            $search = $request->search_value;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->has('role') && $request->role != '') {
            $query->role($request->role);
        }

        return $query->latest('users.created_at');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
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
            Column::make('roles')->title('Role'),
            Column::make('subscription')->title('Subscription'),
            Column::make('created')
                ->name('users.created_at')
                ->title('Created')
                ->orderable(true)
                ->searchable(false),
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
        return 'User_' . date('YmdHis');
    }
}
