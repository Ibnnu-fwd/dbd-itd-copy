<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {

        // dont check for foreign key when delete
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Province::truncate();
        Regency::truncate();
        District::truncate();
        Village::truncate();


        // read csv file and insert data into database
        $file = fopen(database_path('dumps/provinces.csv'), 'r');
        $data = [];
        while (($row = fgetcsv($file, 0, ',')) !== false) {
            $data[] = [
                'id' => $row[0],
                'name' => $row[1],
            ];
        }
        fclose($file);
        Province::insert($data);

        // read csv file and insert data into database
        $file = fopen(database_path('dumps/regencies.csv'), 'r');
        $data = [];
        while (($row = fgetcsv($file, 0, ',')) !== false) {
            $data[] = [
                'id' => $row[0],
                'province_id' => $row[1],
                'name' => $row[2],
            ];
        }

        fclose($file);
        Regency::insert($data);

        // read csv file and insert data into database
        $file = fopen(database_path('dumps/districts.csv'), 'r');
        $data = [];
        while (($row = fgetcsv($file, 0, ',')) !== false) {
            $data[] = [
                'id' => $row[0],
                'regency_id' => $row[1],
                'name' => $row[2],
            ];
        }

        fclose($file);
        District::insert($data);

        // read csv file and insert data into database
        $file = fopen(database_path('dumps/villages.csv'), 'r');
        $data = [];
        while (($row = fgetcsv($file, 0, ',')) !== false) {
            $data[] = [
                'id' => $row[0],
                'district_id' => $row[1],
                'name' => $row[2],
            ];
        }

        fclose($file);
        $chunks = array_chunk($data, 5000);
        foreach ($chunks as $chunk) {
            Village::insert($chunk);
        }
    }
}
