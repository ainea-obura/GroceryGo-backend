<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Role::create(['name' => 'user']);
        //$role->givePermissionTo(['edit articles', 'delete articles']);
        //$userPermissions = Permission::query()
            //->where('name', 'product-list')
            //->orWhere('name', 'user.edit')
            //->orWhere('name', 'product.index')
            //->pluck('id')->toArray();

        //$user->givePermissionTo($userPermissions);
        $user->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }
}
