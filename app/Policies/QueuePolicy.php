<?php

namespace App\Policies;

use App\Models\Queue;
use App\Models\User;

class QueuePolicy
{
    public function viewPatientQueue(User $user, Queue $queue): bool
    {
        return $user->id === $queue->user_id || $user->role === 'admin' || $user->role === 'receptionist' || $user->role === 'doctor';
    }

    public function callNext(User $user, Queue $queue): bool
    {
        return $user->role === 'receptionist';
    }

    public function skip(User $user, Queue $queue): bool
    {
        return $user->role === 'receptionist';
    }

    public function complete(User $user, Queue $queue): bool
    {
        return $user->role === 'doctor';
    }
}
