<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'andi@karhutla.test'],
            [
                'name' => 'Andi',
                'password' => 'password123',
                'role' => 'pelapor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sari@karhutla.test'],
            [
                'name' => 'Sari',
                'password' => 'password123',
                'role' => 'relawan',
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi@karhutla.test'],
            [
                'name' => 'Budi',
                'password' => 'password123',
                'role' => 'koordinator',
            ]
        );
    }
}