<?php

namespace App\Traits;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait Permissions
{

    public function getActionsMap()
    {
        return [
            'o' => 'own',
            'a' => 'approve',
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
        ];
    }

    public function attachPermissionsByRoleNames($roles)
    {
        foreach ($roles as $role_name => $permissions) {
            $role = $this->createRole($role_name);

            foreach ($permissions as $id => $permission) {
                $this->attachPermissionsByAction($role, $id, $permission);
            }
        }
    }

    public function createRole($name, $display_name = null, $description = null)
    {
        $display_name = $display_name ?? Str::title($name);

        return Role::firstOrCreate([
            'name' => $name,
        ], [
            'display_name' => $display_name,
            'guard_name' => 'web',
        ]);
    }

    public function createPermission($name, $display_name = null, $description = null)
    {
        $display_name = $display_name ?? $this->getPermissionDisplayName($name);

        return Permission::firstOrCreate([
            'name' => $name,
        ], [
            'display_name' => $display_name,
            'guard_name' => 'web',
        ]);
    }

    public function getPermissionDisplayName($name)
    {
        if (!empty($this->alias)) {
            $name = str_replace($this->alias, '{Module Placeholder}', $name);
        }

        return Str::title(str_replace('-', ' ', $name));
    }

    public function attachPermissionsByAction($role, $page, $action_list)
    {
        $this->applyPermissionsByAction('attach', $role, $page, $action_list);
    }

    public function applyPermissionsByAction($apply, $role, $page, $action_list)
    {
        $function = $apply . 'Permission';

        $actions_map = collect($this->getActionsMap());

        $actions = explode(',', $action_list);

        foreach ($actions as $short_action) {
            $action = $actions_map->get($short_action);

            $name = $action . '-' . $page;

            $this->$function($role, $name);
        }
    }

    public function attachPermission($role, $permission)
    {
        if (is_string($permission))
        {
            $permission = $this->createPermission($permission);
        }

        if ($role->hasPermissionTo($permission->name)) {
            return;
        }

        $role->givePermissionTo($permission);
    }

    public function detachPermission($role, $permission, $delete = true)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (empty($role)) {
            return;
        }

        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (empty($permission)) {
            return;
        }

        if ($role->hasPermissionTo($permission->name)) {
            $role->revokePermissionTo($permission);
        }

        if ($delete === false) {
            return;
        }

        $permission->delete();
    }

    /////////////////////I DONT NEED THIS NOW//////////////////////////

    public function attachPermissionsToAdminRoles($permissions)
    {
        $this->applyPermissionsToRoles($this->getDefaultAdminRoles(), 'attach', $permissions);
    }

    public function attachPermissionsToPortalRoles($permissions)
    {
        $this->applyPermissionsToRoles($this->getDefaultPortalRoles(), 'attach', $permissions);
    }

    public function attachPermissionsToAllRoles($permissions, $require = 'read-admin-panel')
    {
        $this->applyPermissionsToRoles($this->getRoles($require), 'attach', $permissions);
    }

    public function detachPermissionsByRoleNames($roles)
    {
        foreach ($roles as $role_name => $permissions) {
            foreach ($permissions as $permission_name) {
                $this->detachPermission($role_name, $permission_name);
            }
        }
    }

    public function detachPermissionsFromAdminRoles($permissions)
    {
        $this->applyPermissionsToRoles($this->getDefaultAdminRoles(), 'detach', $permissions);
    }

    public function detachPermissionsFromPortalRoles($permissions)
    {
        $this->applyPermissionsToRoles($this->getDefaultPortalRoles(), 'detach', $permissions);
    }

    public function detachPermissionsFromAllRoles($permissions, $require = 'read-admin-panel')
    {
        $this->applyPermissionsToRoles($this->getRoles($require), 'detach', $permissions, $require);
    }

    public function applyPermissionsToRoles($roles, $apply, $permissions)
    {
        $roles->each(function ($role) use ($apply, $permissions) {
            $f1 = $apply . 'PermissionsByAction';
            $f2 = $apply . 'Permission';

            foreach ($permissions as $id => $permission) {
                if ($this->isActionList($permission)) {
                    $this->$f1($role, $id, $permission);

                    continue;
                }

                $this->$f2($role, $permission);
            }
        });
    }

    public function updatePermissionNames($permissions)
    {
        $actions = $this->getActionsMap();

        foreach ($permissions as $old => $new) {
            foreach ($actions as $action) {
                $old_name = $action . '-' . $old;

                $permission = Permission::where('name', $old_name)->first();

                if (empty($permission)) {
                    continue;
                }

                $new_name = $action . '-' . $new;
                $new_display_name = $this->getPermissionDisplayName($new_name);

                $permission->update([
                    'name' => $new_name,
                    'display_name' => $new_display_name,
                ]);
            }
        }
    }

    public function attachDefaultModulePermissions($module, $require = null)
    {
        $this->attachModuleReportPermissions($module, $require);

        $this->attachModuleWidgetPermissions($module, $require);

        $this->attachModuleSettingPermissions($module, $require);
    }

    public function attachModuleReportPermissions($module, $require = null)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (empty($module->get('reports'))) {
            return;
        }

        $permissions = [];

        foreach ($module->get('reports') as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $permissions[] = $this->createModuleReportPermission($module, $class);
        }

        $require
            ? $this->attachPermissionsToAllRoles($permissions, $require)
            : $this->attachPermissionsToAdminRoles($permissions);
    }

    public function attachModuleWidgetPermissions($module, $require = null)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (empty($module->get('widgets'))) {
            return;
        }

        $permissions = [];

        foreach ($module->get('widgets') as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $permissions[] = $this->createModuleWidgetPermission($module, $class);
        }

        $require
            ? $this->attachPermissionsToAllRoles($permissions, $require)
            : $this->attachPermissionsToAdminRoles($permissions);
    }

    public function attachModuleSettingPermissions($module, $require = null)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (empty($module->get('settings'))) {
            return;
        }

        $permissions = [];

        $permissions[] = $this->createModuleSettingPermission($module, 'read');
        $permissions[] = $this->createModuleSettingPermission($module, 'update');

        $require
            ? $this->attachPermissionsToAllRoles($permissions, $require)
            : $this->attachPermissionsToAdminRoles($permissions);
    }

    public function createModuleReportPermission($module, $class)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (!class_exists($class)) {
            return;
        }

        $name = Reports::getPermission($class);
        $display_name = 'Read ' . $module->getName() . ' Reports ' . Reports::getDefaultName($class);

        return $this->createPermission($name, $display_name);
    }

    public function createModuleWidgetPermission($module, $class)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        if (!class_exists($class)) {
            return;
        }

        $name = Widgets::getPermission($class);
        $display_name = 'Read ' . $module->getName() . ' Widgets ' . Widgets::getDefaultName($class);

        return $this->createPermission($name, $display_name);
    }

    public function createModuleSettingPermission($module, $action)
    {
        return $this->createModuleControllerPermission($module, $action, 'settings');
    }

    public function createModuleControllerPermission($module, $action, $controller)
    {
        if (is_string($module)) {
            $module = module($module);
        }

        $name = $action . '-' . $module->getAlias() . '-' . $controller;
        $display_name = Str::title($action) . ' ' . $module->getName() . ' ' . Str::title($controller);

        return $this->createPermission($name, $display_name);
    }




    public function isActionList($permission)
    {
        if (!is_string($permission)) {
            return false;
        }

        // c || c,r,u,d
        return (Str::length($permission) == 1) || Str::contains($permission, ',');
    }



    public function detachPermissionsByAction($role, $page, $action_list)
    {
        $this->applyPermissionsByAction('detach', $role, $page, $action_list);
    }



    public function getRoles($require = 'read-admin-panel')
    {
        return Role::all()->filter(function ($role) use ($require) {
            return $require ? $role->hasPermission($require) : true;
        });
    }

    public function getDefaultAdminRoles($custom = null)
    {
        $roles = Role::whereIn('name', $custom ?? ['admin', 'it-support'])->get();

        if ($roles->isNotEmpty()) {
            return $roles;
        }

        return $this->getRoles('read-admin-panel');
    }

    public function getDefaultPortalRoles($custom = null)
    {
        $roles = Role::whereIn('name', $custom ?? ['customer'])->get();

        if ($roles->isNotEmpty()) {
            return $roles;
        }

        return $this->getRoles('read-client-portal');
    }

    /**
     * Assign permissions middleware to default controller methods.
     *
     * @return void
     */
    public function assignPermissionsToController()
    {
        // No need to check for permission in console
        if (app()->runningInConsole()) {
            return;
        }

        $controller = get_permission_name();

//        dd($controller);

        // Skip ACL
        $skip = ['portal-dashboard'];
        if (in_array($controller, $skip)) {
            return;
        }
//        dd($controller);

        // Add CRUD permission check
        $this->middleware('permission:create-' . $controller)->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-' . $controller)->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-' . $controller)->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-' . $controller)->only('destroy');
    }

    public function canAccessMenuItem($title, $permissions)
    {
        $permissions = Arr::wrap($permissions);

        $item = new \stdClass();
        $item->title = $title;
        $item->permissions = $permissions;

        event(new \App\Events\Menu\ItemAuthorizing($item));

        return user()->canAny($item->permissions);
    }
}
