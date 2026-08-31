<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-6">
        <div class="bg-white rounded-xl shadow p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold">My Queue</h1>
                    <p class="text-gray-500 mt-2">Tracking status and estimated wait time for your appointment.</p>
                </div>
                <div>
                    <span id="queue-status" class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700 capitalize">
                        {{ ucfirst($queue->status) }}
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="mt-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">{{ session('error') }}</div>
            @endif

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="space-y-4 rounded-xl bg-slate-50 p-6">
                    <div>
                        <p class="text-sm text-gray-500">Department</p>
                        <p class="text-xl font-semibold">{{ $queue->department->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Queue Number</p>
                        <p id="queue-number" class="text-xl font-semibold">{{ $queue->queue_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Current Number Being Served</p>
                        <p id="current-serving" class="text-xl font-semibold">{{ $currentServing?->queue_number ?? 'Not started' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Joined At</p>
                        <p class="text-xl font-semibold">{{ $queue->joined_at->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="space-y-4 rounded-xl bg-slate-50 p-6">
                    <div>
                        <p class="text-sm text-gray-500">Patients Ahead</p>
                        <p id="patients-ahead" class="text-xl font-semibold">{{ $patientsAhead }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Estimated Waiting Time</p>
                        <p id="estimated-wait" class="text-xl font-semibold">{{ $estimatedWait }} minutes</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Average Consultation</p>
                        <p class="text-xl font-semibold">{{ $queue->department->average_consultation_time }} minutes</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold">Queue Details</h2>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-gray-500">Patient</dt>
                        <dd class="mt-1 text-gray-900">{{ $queue->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $queue->queue_date->format('F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd id="status-text" class="mt-1 text-gray-900 capitalize">{{ $queue->status }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Called At</dt>
                        <dd class="mt-1 text-gray-900">{{ $queue->called_at?->format('h:i A') ?? 'Not yet called' }}</dd>
                    </div>
                </dl>
            </div>

            @if(in_array($queue->status, ['waiting', 'serving'], true))
                <div class="mt-8">
                    <form method="POST" action="{{ route('queue.cancel', $queue) }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700">Cancel Queue</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        const statusUrl = '{{ route('queue.status', $queue) }}';
        const statusNode = document.getElementById('queue-status');
        const patientAheadNode = document.getElementById('patients-ahead');
        const estimatedWaitNode = document.getElementById('estimated-wait');
        const statusTextNode = document.getElementById('status-text');
        const currentServingNode = document.getElementById('current-serving');

        function refreshQueueStatus() {
            fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
                .then(response => response.ok ? response.json() : null)
                .then(data => {
                    if (!data) return;
                    statusNode.textContent = data.status;
                    statusTextNode.textContent = data.status;
                    patientAheadNode.textContent = data.patients_ahead;
                    estimatedWaitNode.textContent = data.estimated_wait_minutes + ' minutes';
                    currentServingNode.textContent = data.current_serving || 'Not started';
                })
                .catch(() => {});
        }

        setInterval(refreshQueueStatus, 10000);
    </script>
</x-app-layout>
