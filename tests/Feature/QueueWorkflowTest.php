<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueueWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function createDepartment(array $attributes = []): Department
    {
        return Department::create([
            'name' => 'General Medicine',
            'code' => 'GEN',
            'description' => 'General consultation',
            'average_consultation_time' => 15,
            ...$attributes,
        ]);
    }

    protected function createUser(string $role, string $email): User
    {
        return User::create([
            'first_name' => ucfirst($role),
            'last_name' => 'User',
            'phone' => '0917000000' . strlen($email),
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => $role,
        ]);
    }

    public function test_patient_can_join_queue_and_see_wait_time(): void
    {
        $patient = $this->createUser('patient', 'patient@example.com');
        $department = $this->createDepartment();

        $this->actingAs($patient);

        $response = $this->post(route('queue.join', $department));

        $response->assertRedirect();
        $queue = Queue::first();
        $this->assertNotNull($queue);
        $this->assertSame('waiting', $queue->status);

        $this->get(route('queue.show', $queue))
            ->assertOk()
            ->assertSee('Estimated Waiting Time')
            ->assertSee('Patients Ahead');
    }

    public function test_receptionist_can_call_next_and_doctor_can_complete(): void
    {
        $receptionist = $this->createUser('receptionist', 'receptionist@example.com');
        $doctor = $this->createUser('doctor', 'doctor@example.com');
        $patient = $this->createUser('patient', 'patient2@example.com');
        $department = $this->createDepartment();

        $queue = Queue::create([
            'user_id' => $patient->id,
            'department_id' => $department->id,
            'queue_number' => 'GEN-001',
            'status' => 'waiting',
            'queue_date' => today(),
            'joined_at' => now(),
            'doctor_id' => $doctor->id,
        ]);

        $this->actingAs($receptionist);
        $this->post(route('receptionist.call', $queue), ['doctor_id' => $doctor->id])
            ->assertRedirect();

        $this->assertSame('serving', $queue->fresh()->status);
        $this->assertSame($doctor->id, $queue->fresh()->doctor_id);

        $this->actingAs($doctor);
        $this->post(route('doctor.complete', $queue))
            ->assertRedirect();

        $this->assertSame('completed', $queue->fresh()->status);
    }

    public function test_unauthorized_users_cannot_call_or_complete_queue(): void
    {
        $patient = $this->createUser('patient', 'patient3@example.com');
        $doctor = $this->createUser('doctor', 'doctor2@example.com');
        $department = $this->createDepartment();
        $queue = Queue::create([
            'user_id' => $patient->id,
            'department_id' => $department->id,
            'queue_number' => 'GEN-001',
            'status' => 'waiting',
            'queue_date' => today(),
            'joined_at' => now(),
            'doctor_id' => $doctor->id,
        ]);

        $this->actingAs($patient);
        $this->post(route('receptionist.call', $queue), ['doctor_id' => $doctor->id])->assertStatus(403);
        $this->post(route('doctor.complete', $queue))->assertStatus(403);
    }
}
