<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateManagerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Super Manager',
            'email' => 'manager@manager.com',
            'password' => Hash::make(123123),
        ]);

        $role = Role::create(['name' => 'Manager']);
        $user->assignRole([$role->id]);
        $permission = Permission::where(['name' => 'application-list'])->first();


        $role->givePermissionTo($permission);
    }
}
