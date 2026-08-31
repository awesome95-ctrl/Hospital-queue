<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $date = $request->date ? $request->date : today()->toDateString();
        $query = Queue::with(['user', 'department'])
            ->whereDate('queue_date', $date)
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->department_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderBy('joined_at');

        $queuesToday = $query->paginate(20)->withQueryString();

        $stats = [
            'waiting' => Queue::whereDate('queue_date', $date)->where('status', 'waiting')->count(),
            'serving' => Queue::whereDate('queue_date', $date)->where('status', 'serving')->count(),
            'skipped' => Queue::whereDate('queue_date', $date)->where('status', 'skipped')->count(),
            'completed' => Queue::whereDate('queue_date', $date)->where('status', 'completed')->count(),
            'cancelled' => Queue::whereDate('queue_date', $date)->where('status', 'cancelled')->count(),
            'total' => Queue::whereDate('queue_date', $date)->count(),
        ];

        $departments = Department::orderBy('name')->get();

        return view('admin.dashboard', compact('queuesToday', 'stats', 'departments'));
    }
}
