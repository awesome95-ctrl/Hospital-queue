<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Queue;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        $activeQueue = Queue::with('department')
            ->where('user_id', Auth::id())
            ->whereDate('queue_date', today())
            ->whereIn('status', ['waiting', 'serving'])
            ->latest('joined_at')
            ->first();

        return view('patient.dashboard', compact('departments', 'activeQueue'));
    }
}