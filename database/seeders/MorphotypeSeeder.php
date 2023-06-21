<?php

namespace Database\Seeders;

use App\Models\Morphotype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MorphotypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $morphotypes = [
            ['name' => 'Morfotipe 1'],
            ['name' => 'Morfotipe 2'],
            ['name' => 'Morfotipe 3'],
            ['name' => 'Morfotipe 4'],
            ['name' => 'Morfotipe 5'],
            ['name' => 'Morfotipe 6'],
            ['name' => 'Morfotipe 7'],
        ];

        Morphotype::insert($morphotypes);
    }
}
