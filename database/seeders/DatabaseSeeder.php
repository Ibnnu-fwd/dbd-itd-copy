<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\LocationType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            UserSeeder::class,
            VirusSeeder::class,
            SerotypeSeeder::class,
            MorphotypeSeeder::class,
        ]);
    }
}
