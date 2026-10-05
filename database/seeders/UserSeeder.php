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
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'Superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hijriah',
                'email' => 'hijriah@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'Akuntan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mbak Syafi',
                'email' => 'mbaksyafi@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'QC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bapak Eko',
                'email' => 'eko@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'AG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        User::insert($data);
    }
}
