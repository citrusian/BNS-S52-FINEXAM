<?php

namespace Database\Seeders;

use App\Models\ABarang;
use App\Models\ANomorSeri;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    use WithoutModelEvents;
    public function run()
    {
//      Manual Seeder Input
        DB::table('users')->insert([
            'role' => '0',
            'username' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin')
        ]);

//      Call Additional Seeder Using Factories
//        $this->call([
//            ABarangSeeder::class,
//        ]);

//        Disabled, idk why but it use count = 10, not portable when i want to use count =15


        ABarang::factory()
            ->count(10)
            ->create();

        ANomorSeri::factory()
            ->count(10)
            ->create();

        User::factory()
            ->count(5)
            ->create();
    }
}
