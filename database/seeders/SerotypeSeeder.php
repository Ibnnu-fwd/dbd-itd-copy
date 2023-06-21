<?php

namespace Database\Seeders;

use App\Models\Serotype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SerotypeSeeder extends Seeder
{
    public function run(): void
    {
        // make serotypes DENV 1-4
        $serotypes = [
            ['name' => 'DENV 1'],
            ['name' => 'DENV 2'],
            ['name' => 'DENV 3'],
            ['name' => 'DENV 4'],
        ];

        Serotype::insert($serotypes);
    }
}
