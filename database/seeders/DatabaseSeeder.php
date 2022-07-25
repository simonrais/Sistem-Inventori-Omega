<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,
            KategoriSeeder::class,
            RiwayatSeeder::class,
        ]);

        $user = User::create([
            'name' => 'estimator',
            'username' => 'estimator',
            'password' => bcrypt('user')
        ]);

        $user->assignRole('estimator');
        Gudang::factory(10)->create();
        Barang::factory(10)->create();
    }
}
