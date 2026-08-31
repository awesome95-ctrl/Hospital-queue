<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Queue;
use App\Services\QueueWaitTimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    public function __construct(private QueueWaitTimeService $queueWaitTimeService)
    {
    }

    public function confirm(Department $department)
    {
        return view('queue.confirm', compact('department'));
    }

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

    public function show(Queue $queue)
    {
        if ($queue->user_id !== Auth::id()) {
            abort(403);
        }

        $patientsAhead = $this->queueWaitTimeService->patientsAhead($queue);
        $estimatedWait = $this->queueWaitTimeService->estimatedWaitingMinutes($queue);
        $currentServing = $this->queueWaitTimeService->currentPatientBeingServed($queue->department_id, $queue->queue_date->toDateString());

        return view('queue.show', compact('queue', 'patientsAhead', 'estimatedWait', 'currentServing'));
    }

    public function status(Queue $queue)
    {
        if ($queue->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'status' => $queue->status,
            'queue_number' => $queue->queue_number,
            'patients_ahead' => $this->queueWaitTimeService->patientsAhead($queue),
            'estimated_wait_minutes' => $this->queueWaitTimeService->estimatedWaitingMinutes($queue),
            'current_serving' => $this->queueWaitTimeService->currentPatientBeingServed($queue->department_id, $queue->queue_date->toDateString())?->queue_number,
            'last_updated' => now()->toIso8601String(),
        ]);
    }

    public function cancel(Queue $queue)
    {
        if ($queue->user_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array($queue->status, ['waiting', 'serving'], true)) {
            return back()->with('error', 'Only active queue entries can be cancelled.');
        }

        $queue->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Your queue entry has been cancelled.');
    }
}