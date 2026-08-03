<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index()
    {
        abort_if(Auth::user()->role !== 'doctor', 403);

        $waitingQueues = Queue::with(['user', 'department'])
            ->where('status', 'waiting')
            ->whereDate('queue_date', today())
            ->orderBy('joined_at')
            ->get();

        return view('doctor.dashboard', compact('waitingQueues'));
    }

    public function callNext(Queue $queue)
    {
        abort_if(Auth::user()->role !== 'doctor', 403);

        if ($queue->status !== 'waiting') {
            return back()->with('error', 'Only waiting patients can be called.');
        }

        $queue->update([
            'status' => 'serving',
            'called_at' => now(),
        ]);

        return back()->with('success', 'Patient has been called to serving.');
    }

    public function skip(Queue $queue)
    {
        abort_if(Auth::user()->role !== 'doctor', 403);

        if ($queue->status !== 'waiting') {
            return back()->with('error', 'Only waiting patients can be skipped.');
        }

        $queue->update([
            'status' => 'skipped',
        ]);

        return back()->with('success', 'Patient has been skipped.');
    }

    public function complete(Queue $queue)
    {
        abort_if(Auth::user()->role !== 'doctor', 403);

        if ($queue->status === 'completed') {
            return back()->with('error', 'Queue is already completed.');
        }

        $queue->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Patient has been marked completed.');
    }
}
