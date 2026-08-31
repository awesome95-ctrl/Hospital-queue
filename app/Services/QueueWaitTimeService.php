<?php

namespace App\Services;

use App\Models\Queue;
use Illuminate\Support\Facades\DB;

class QueueWaitTimeService
{
    public function patientsAhead(Queue $queue): int
    {
        return Queue::query()
            ->where('department_id', $queue->department_id)
            ->whereDate('queue_date', $queue->queue_date)
            ->whereIn('status', ['waiting', 'serving'])
            ->where(function ($query) use ($queue) {
                $query->where('joined_at', '<', $queue->joined_at)
                    ->orWhere(function ($nested) use ($queue) {
                        $nested->where('joined_at', $queue->joined_at)
                            ->where('id', '<', $queue->id);
                    });
            })
            ->count();
    }

    public function currentPatientBeingServed(int $departmentId, string $queueDate): ?Queue
    {
        return Queue::query()
            ->with(['user', 'department'])
            ->where('department_id', $departmentId)
            ->whereDate('queue_date', $queueDate)
            ->where('status', 'serving')
            ->orderBy('called_at')
            ->first();
    }

    public function estimatedWaitingMinutes(Queue $queue): int
    {
        $patientsAhead = $this->patientsAhead($queue);

        if ($patientsAhead < 1) {
            return 0;
        }

        $historicalAverage = Queue::query()
            ->where('department_id', $queue->department_id)
            ->where('status', 'completed')
            ->whereNotNull('called_at')
            ->whereNotNull('completed_at')
            ->whereRaw('datetime(completed_at) > datetime(called_at)')
            ->select(DB::raw($this->averageMinutesSql()))
            ->value('average_minutes');

        $serviceMinutes = $historicalAverage !== null && $historicalAverage > 0
            ? (int) round((float) $historicalAverage)
            : config('hospital.default_service_minutes', 15);

        return max(0, $patientsAhead * $serviceMinutes);
    }

    protected function averageMinutesSql(): string
    {
        $driver = DB::getDriverName();

        return match ($driver) {
            'mysql' => 'AVG(TIMESTAMPDIFF(MINUTE, called_at, completed_at)) as average_minutes',
            'sqlite' => 'AVG((strftime("%s", completed_at) - strftime("%s", called_at)) / 60) as average_minutes',
            default => 'AVG((unix_timestamp(completed_at) - unix_timestamp(called_at)) / 60) as average_minutes',
        };
    }
}
