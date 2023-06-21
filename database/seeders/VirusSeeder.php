<?php

namespace Database\Seeders;

use App\Models\Virus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VirusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $viruses = [
            ['name' => 'Aedes Aegepty'],
            ['name' => 'Aedes Albopictus'],
            ['name' => 'Culex']
        ];

        Virus::insert($viruses);
    }
}
