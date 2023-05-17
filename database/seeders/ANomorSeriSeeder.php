<?php

namespace Database\Seeders;

use App\Models\ANomorSeri;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ANomorSeriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ANomorSeri::factory()
            ->count(10)
            ->create();
    }
}
