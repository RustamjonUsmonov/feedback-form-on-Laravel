<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Дефолтные роли.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Client', 'guard_name' => 'web']);
        $permission = Permission::where(['name' => 'application-create'])->first();

        $role->givePermissionTo($permission->id);
    }
}
