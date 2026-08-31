<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index()
    {
        abort_if(! in_array(Auth::user()->role, ['doctor', 'admin'], true), 403);

        $waitingQueues = Queue::with(['user', 'department'])
            ->where('status', 'waiting')
            ->whereDate('queue_date', today())
            ->orderBy('joined_at')
            ->get();

        $servedQueues = Queue::with(['user', 'department'])
            ->where('status', 'serving')
            ->whereDate('queue_date', today())
            ->where('doctor_id', Auth::id())
            ->orderBy('called_at')
            ->get();

        return view('doctor.dashboard', compact('waitingQueues', 'servedQueues'));
    }

    public function callNext(Queue $queue)
    {
        abort_unless(Auth::user()->role === 'receptionist' || Auth::user()->role === 'admin', 403);

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
        abort_unless(Auth::user()->role === 'receptionist' || Auth::user()->role === 'admin', 403);

        if ($queue->status !== 'waiting') {
            return back()->with('error', 'Only waiting patients can be skipped.');
        }

        $queue->update([
            'status' => 'skipped',
            'skipped_at' => now(),
        ]);

        return back()->with('success', 'Patient has been skipped.');
    }

    public function complete(Queue $queue)
    {
        abort_unless(Auth::user()->role === 'doctor' || Auth::user()->role === 'admin', 403);

        if ($queue->status === 'completed') {
            return back()->with('error', 'Queue is already completed.');
        }

        if ($queue->status !== 'serving' && $queue->status !== 'waiting') {
            return back()->with('error', 'Only serving or waiting patients can be completed.');
        }

        $queue->update([
            'status' => 'completed',
            'doctor_id' => Auth::id(),
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Patient has been marked completed.');
    }
}
