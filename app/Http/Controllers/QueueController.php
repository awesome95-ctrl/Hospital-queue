<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    /**
     * Show the confirmation page.
     */
    public function confirm(Department $department)
    {
        return view('queue.confirm', compact('department'));
    }

    /**
     * Join the queue.
     */
    public function join(Request $request, Department $department)
    {
        $user = Auth::user();

        $existingQueue = Queue::where('user_id', $user->id)
            ->whereDate('queue_date', today())
            ->whereIn('status', ['waiting', 'serving'])
            ->first();

        if ($existingQueue) {
            return redirect()->route('queue.show', $existingQueue)
                ->with('error', 'You already have an active queue.');
        }

        $lastQueue = Queue::where('department_id', $department->id)
            ->whereDate('queue_date', today())
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;

        if ($lastQueue) {
            $lastNumber = (int) substr($lastQueue->queue_number, strrpos($lastQueue->queue_number, '-') + 1);
            $nextNumber = $lastNumber + 1;
        }

        $queueNumber = $department->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $queue = Queue::create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'queue_number' => $queueNumber,
            'status' => 'waiting',
            'queue_date' => today(),
            'joined_at' => now(),
        ]);

        return redirect()->route('queue.show', $queue)
            ->with('success', 'You have joined the queue.');
    }

    /**
     * Display a patient's queue.
     */
    public function show(Queue $queue)
    {
        if ($queue->user_id !== Auth::id()) {
            abort(403);
        }

        $patientsAhead = Queue::where('department_id', $queue->department_id)
            ->whereDate('queue_date', $queue->queue_date)
            ->where('status', 'waiting')
            ->where('joined_at', '<', $queue->joined_at)
            ->count();

        $estimatedWait = $patientsAhead * $queue->department->average_consultation_time;

        return view('queue.show', compact('queue', 'patientsAhead', 'estimatedWait'));
    }
}