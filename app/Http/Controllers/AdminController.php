<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Queue;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $today = today();

        $queuesToday = Queue::with(['user', 'department'])
            ->whereDate('queue_date', $today)
            ->orderBy('joined_at')
            ->get();

        $stats = [
            'waiting' => Queue::whereDate('queue_date', $today)->where('status', 'waiting')->count(),
            'serving' => Queue::whereDate('queue_date', $today)->where('status', 'serving')->count(),
            'skipped' => Queue::whereDate('queue_date', $today)->where('status', 'skipped')->count(),
            'completed' => Queue::whereDate('queue_date', $today)->where('status', 'completed')->count(),
            'cancelled' => Queue::whereDate('queue_date', $today)->where('status', 'cancelled')->count(),
            'total' => Queue::whereDate('queue_date', $today)->count(),
        ];

        $departments = Department::orderBy('name')->get();

        return view('admin.dashboard', compact('queuesToday', 'stats', 'departments'));
    }
}
