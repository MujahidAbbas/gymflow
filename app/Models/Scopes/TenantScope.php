<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Only apply scope if user is authenticated
        if (! auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super admin can see everything - bypass scope
        if ($user->hasRole('super-admin')) {
            return;
        }

        // Determine the parent_id to filter by
        if ($user->hasRole('owner')) {
            // Owner sees their own data (where parent_id = their ID)
            $parentId = $user->id;
        } else {
            // Staff/members see data belonging to their owner (where parent_id = their parent's ID)
            $parentId = $user->parent_id;
        }

        // Apply the filter
        if ($parentId) {
            $builder->where($model->getTable().'.parent_id', $parentId);
        }
    }
}
