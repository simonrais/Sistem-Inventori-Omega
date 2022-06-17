<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => bcrypt('admin')
        ]);

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

        $this->createAllRoles();
    }

    public function createAllRoles()
    {
        $staffGudang = Role::create(['name' => 'Staff Gudang']);
        $staffBarang = Role::create(['name' => 'Staff Barang']);

        $staffGudang->syncPermissions([12]);
        $staffBarang->syncPermissions([11]);
    }
}
