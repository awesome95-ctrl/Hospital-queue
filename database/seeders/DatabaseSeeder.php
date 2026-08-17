<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(DepartmentSeeder::class);

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'phone' => '09171234567',
                'password' => Hash::make('password'),
                'role' => 'patient',
            ]
        );

        User::updateOrCreate(
            ['email' => 'doctor@example.com'],
            [
                'first_name' => 'Doctor',
                'last_name' => 'Who',
                'phone' => '09171234568',
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '09171234569',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}