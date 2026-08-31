<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Queue;
use App\Services\QueueWaitTimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistController extends Controller
{
    public function __construct(private QueueWaitTimeService $queueWaitTimeService)
    {
    }

    public function index(Request $request)
    {
        abort_unless(Auth::user()->role === 'receptionist' || Auth::user()->role === 'admin', 403);

        $query = Queue::with(['user', 'department', 'doctor'])
            ->whereDate('queue_date', $request->date ?? today())
            ->orderBy('joined_at');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $queues = $query->paginate(20)->withQueryString();
        $departments = Department::orderBy('name')->get();
        $currentQueues = Queue::with(['user', 'department'])
            ->whereDate('queue_date', $request->date ?? today())
            ->where('status', 'serving')
            ->orderBy('called_at')
            ->get();

        return view('receptionist.dashboard', compact('queues', 'departments', 'currentQueues'));
    }

    public function callNext(Request $request, Queue $queue)
    {
        abort_unless(Auth::user()->role === 'receptionist' || Auth::user()->role === 'admin', 403);

        if ($queue->status !== 'waiting') {
            return back()->with('error', 'Only waiting patients can be called.');
        }

        $existingServing = Queue::where('department_id', $queue->department_id)
            ->whereDate('queue_date', $queue->queue_date)
            ->where('status', 'serving')
            ->whereKeyNot($queue->id)
            ->first();

        if ($existingServing) {
            return back()->with('error', 'Another patient is already being served in this department.');
        }

        $queue->update([
            'status' => 'serving',
            'called_at' => now(),
            'doctor_id' => $request->filled('doctor_id') ? (int) $request->doctor_id : $queue->doctor_id,
        ]);

        return back()->with('success', 'Patient has been called to the consultation desk.');
    }

    public function skip(Queue $queue)
    {
        abort_unless(Auth::user()->role === 'receptionist' || Auth::user()->role === 'admin', 403);

        if ($queue->status !== 'waiting' && $queue->status !== 'serving') {
            return back()->with('error', 'Only waiting or serving patients can be skipped.');
        }

        $queue->update([
            'status' => 'skipped',
            'skipped_at' => now(),
        ]);

        return back()->with('success', 'Patient has been skipped successfully.');
    }
}
