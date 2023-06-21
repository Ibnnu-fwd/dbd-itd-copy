<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'sex' => 1,
            'birthday' => '1990-01-01',
            'address' => 'Indonesia',
            'phone' => '+6281515144981',
            'email' => 'admin@mail.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'role' => 'admin'
        ]);
    }
}
