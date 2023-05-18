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

//      Call Additional Seeder Using Factories
//        $this->call([
//            ABarangSeeder::class,
//            ANomorSeriSeeder::class,
//            BTransaksiSeeder::class,
//        ]);
        // Disabled, more easy to define number in here than *Seeder.php

        User::factory()
            ->count(15)
            ->create();

        ABarang::factory()
            ->count(50)
            ->create();

        ANomorSeri::factory()
            ->count(50)
            ->create();

        // Used to dynamically calculate random data input
        $this->call([
            BTransaksiSeeder::class,
            BDetailTransaksiSeeder::class,
        ]);
    }
}
