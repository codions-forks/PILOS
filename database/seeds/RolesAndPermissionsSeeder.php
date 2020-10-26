<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Creates the default roles and permissions and assigns to all existing
     * users a default user role if not already set.
     *
     * @return void
     */
    public function run()
    {
        $adminPermissions = [];

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'rooms.create' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'rooms.delete' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'settings.manage' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'settings.viewAny' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'settings.update' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.viewAny' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.view' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.create' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.update' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.delete' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'users.viewAny' ])->id;

        $adminRole = Role::firstOrCreate([ 'name' => 'admin', 'default' => true, 'role_limit' => -1 ]);
        $adminRole->permissions()->syncWithoutDetaching($adminPermissions);
    }
}
