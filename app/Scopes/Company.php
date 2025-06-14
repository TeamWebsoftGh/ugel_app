<?php

namespace App\Scopes;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Company implements Scope
{
    use Scopes;

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (method_exists($model, 'isNotTenantable') && $model->isNotTenantable()) {
            return;
        }

        $table = $model->getTable();

        // Skip for specific tables
        $skip_tables = [
            'jobs', 'migrations', 'notifications', 'role_companies', 'users','client_types',
            'role_permissions', 'sessions', 'user_companies', 'permissions', 'user_roles',
        ];

        if (in_array($table, $skip_tables)) {
            return;
        }

//        if (is_owner_company()) {
//            return;
//        }

        // Skip if already exists
        if ($this->scopeColumnExists($builder, '', 'company_id')) {
            return;
        }

        // Apply company scope
        $builder->where($table . '.company_id', '=', user()?->company_id);
    }
}
