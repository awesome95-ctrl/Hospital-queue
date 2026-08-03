<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(DepartmentSeeder::class);

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '09171234567',
            'email' => 'test@example.com',
            'role' => 'patient',
        ]);

        User::factory()->create([
            'first_name' => 'Doctor',
            'last_name' => 'Who',
            'phone' => '09171234568',
            'email' => 'doctor@example.com',
            'role' => 'doctor',
        ]);

        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'phone' => '09171234569',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }
}
