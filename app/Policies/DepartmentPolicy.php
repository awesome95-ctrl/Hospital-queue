<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'receptionist';
    }

    public function manage(User $user, Department $department): bool
    {
        return $user->role === 'admin';
    }
}
