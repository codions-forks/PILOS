<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RoomType;
use App\Models\ServerPool;
use App\Models\User;
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
        // Check if roles already exists (not a clean installation)
        $freshInstall = Role::all()->count() == 0;

        // Setup default admin role and permissions

        $adminPermissions = [];

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'rooms.create' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'rooms.viewAll' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'rooms.manage' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'meetings.viewAny' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'settings.manage' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'applicationSettings.viewAny' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'applicationSettings.update' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.viewAny' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.view' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.update' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.create' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roles.delete' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'users.viewAny' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'users.view' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'users.update' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'users.create' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'users.delete' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'users.updateOwnAttributes' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roomTypes.view' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roomTypes.update' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roomTypes.create' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'roomTypes.delete' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'servers.viewAny' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'servers.view' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'servers.update' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'servers.create' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'servers.delete' ])->id;

        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'serverPools.viewAny' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'serverPools.view' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'serverPools.update' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'serverPools.create' ])->id;
        $adminPermissions[] = Permission::firstOrCreate([ 'name' => 'serverPools.delete' ])->id;

        $adminRole = Role::where(['name' => 'admin', 'default' => true])->first();
        if ($adminRole == null) {
            $adminRole = Role::create([ 'name' => 'admin', 'default' => true, 'room_limit' => -1 ]);
        }
        $adminRole->permissions()->syncWithoutDetaching($adminPermissions);


        // Setup default user role and permissions on fresh installation
        if($freshInstall) {
            $userRole = Role::create([ 'name' => 'user']);

            $userPermissions = [];
            $userPermissions[] = Permission::firstOrCreate([ 'name' => 'rooms.create' ])->id;
            $userRole->permissions()->syncWithoutDetaching($userPermissions);
        }

        // Remove non-existing permissions
        Permission::whereNotIn('id', $adminPermissions)->delete();

        // Setup permission inheritances
        /// e.g. If you have permission x, you also get the permissions a,b,c
        Permission::setIncludedPermissions('rooms.manage', ['rooms.create','rooms.viewAll']);

        Permission::setIncludedPermissions('meetings.viewAny', ['rooms.viewAll']);

        Permission::setIncludedPermissions('applicationSettings.update', ['applicationSettings.viewAny','settings.manage']);
        Permission::setIncludedPermissions('applicationSettings.viewAny', ['settings.manage']);

        Permission::setIncludedPermissions('roles.delete', ['roles.create','roles.update','roles.view','roles.viewAny','settings.manage']);
        Permission::setIncludedPermissions('roles.create', ['roles.update','roles.view','roles.viewAny','settings.manage']);
        Permission::setIncludedPermissions('roles.update', ['roles.view','roles.viewAny','settings.manage']);
        Permission::setIncludedPermissions('roles.view', ['roles.viewAny','settings.manage']);
        Permission::setIncludedPermissions('roles.viewAny', ['settings.manage']);

        Permission::setIncludedPermissions('users.delete', ['users.updateOwnAttributes','users.create','users.update','users.view','users.viewAny','roles.viewAny','settings.manage']);
        Permission::setIncludedPermissions('users.create', ['users.updateOwnAttributes','users.update','users.view','users.viewAny','roles.viewAny','settings.manage']);
        Permission::setIncludedPermissions('users.update', ['users.updateOwnAttributes','users.view','users.viewAny','roles.viewAny','settings.manage']);
        Permission::setIncludedPermissions('users.view', ['users.viewAny','roles.viewAny','settings.manage']);
        Permission::setIncludedPermissions('users.viewAny', ['settings.manage']);

        Permission::setIncludedPermissions('roomTypes.delete', ['roomTypes.create','roomTypes.update','roomTypes.view','serverPools.viewAny','settings.manage','roles.viewAny']);
        Permission::setIncludedPermissions('roomTypes.create', ['roomTypes.update','roomTypes.view','serverPools.viewAny','settings.manage','roles.viewAny']);
        Permission::setIncludedPermissions('roomTypes.update', ['roomTypes.view','serverPools.viewAny','settings.manage','roles.viewAny']);
        Permission::setIncludedPermissions('roomTypes.view', ['serverPools.viewAny','settings.manage','roles.viewAny']);

        Permission::setIncludedPermissions('servers.delete', ['servers.create','servers.update','servers.view','servers.viewAny','settings.manage']);
        Permission::setIncludedPermissions('servers.create', ['servers.update','servers.view','servers.viewAny','settings.manage']);
        Permission::setIncludedPermissions('servers.update', ['servers.view','servers.viewAny','settings.manage']);
        Permission::setIncludedPermissions('servers.view', ['servers.viewAny','settings.manage']);
        Permission::setIncludedPermissions('servers.viewAny', ['settings.manage']);

        Permission::setIncludedPermissions('serverPools.delete', ['serverPools.create','serverPools.update','serverPools.view','serverPools.viewAny','servers.viewAny', 'settings.manage']);
        Permission::setIncludedPermissions('serverPools.create', ['serverPools.update','serverPools.view','serverPools.viewAny','servers.viewAny', 'settings.manage']);
        Permission::setIncludedPermissions('serverPools.update', ['serverPools.view','serverPools.viewAny','servers.viewAny', 'settings.manage']);
        Permission::setIncludedPermissions('serverPools.view', ['serverPools.viewAny','servers.viewAny', 'settings.manage']);
        Permission::setIncludedPermissions('serverPools.viewAny', ['settings.manage']);
    }
}
