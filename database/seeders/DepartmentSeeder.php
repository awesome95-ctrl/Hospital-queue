<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Department::create([
        'name' => 'General Consultation',
        'code' => 'GC',
    ]);

    \App\Models\Department::create([
        'name' => 'Dental Clinic',
        'code' => 'DEN',
    ]);

    \App\Models\Department::create([
        'name' => 'Eye Clinic',
        'code' => 'EYE',
    ]);

    \App\Models\Department::create([
        'name' => 'Laboratory',
        'code' => 'LAB',
    ]);

    \App\Models\Department::create([
        'name' => 'Pharmacy',
        'code' => 'PHA',
    ]);
}
}
