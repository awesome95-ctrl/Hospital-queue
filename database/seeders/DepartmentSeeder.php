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
        $departments = [
            ['name' => 'General Consultation', 'code' => 'GC'],
            ['name' => 'Dental Clinic', 'code' => 'DEN'],
            ['name' => 'Eye Clinic', 'code' => 'EYE'],
            ['name' => 'Laboratory', 'code' => 'LAB'],
            ['name' => 'Pharmacy', 'code' => 'PHA'],
        ];

        foreach ($departments as $department) {
            \App\Models\Department::updateOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}