<?php

namespace Database\Seeders;

use App\Models\ABarang;
use App\Models\ANomorSeri;
use App\Models\BTransaksi;
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
            'username' => 'supdadmin',
            'firstname' => 'SupAdmin',
            'lastname' => 'Admin',
            'email' => 'supdadmin@admin.com',
            'password' => bcrypt('supdadmin'),
            'address' => "Address",
            'city' => "Jakarta",
            'country' => 'Indonesia',
            'postal' => '111111',
            'about' => "lorem ipsum",
            'pp_path' => "1.jpg",
        ]);

//      Manual Seeder Input
        DB::table('users')->insert([
            'role' => '1',
            'username' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'address' => "Address",
            'city' => "Jakarta",
            'country' => 'Indonesia',
            'postal' => '111111',
            'about' => "lorem ipsum",
            'pp_path' => "2.jpg",
        ]);

        // Use key from .env, or if missing revert to default 100,500
        $usernumber = env('USER_NUMBER', 100);
        $itemnumber = env('ITEM_NUMBER', 500);

        User::factory()
            ->count($usernumber)
            ->create();

        ABarang::factory()
            ->count($itemnumber)
            ->create();

        ANomorSeri::factory()
            ->count($itemnumber)
            ->create();

        // Used to dynamically calculate random data input
        $this->call([
            BTransaksiSeeder::class,
            BDetailTransaksiSeeder::class,
        ]);
    }
}
