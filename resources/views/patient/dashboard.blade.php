<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->first_name }} 👋</h1>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">{{ session('error') }}</div>
        @endif

        @if(isset($activeQueue) && $activeQueue)
            @php
                $service = app(App\Services\QueueWaitTimeService::class);
                $patientsAhead = $service->patientsAhead($activeQueue);
                $estimatedWait = $service->estimatedWaitingMinutes($activeQueue);
                $currentServing = $service->currentPatientBeingServed($activeQueue->department_id, $activeQueue->queue_date->toDateString());
            @endphp

            <div class="mb-8 bg-white rounded-xl shadow p-6 border border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold mb-2">My Queue</h2>
                        <p class="text-gray-500">Track your queue position and estimated wait time.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700 capitalize">
                        {{ $activeQueue->status }}
                    </span>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-4">
                    <div>
                        <p class="text-sm text-gray-500">Department</p>
                        <p class="text-lg font-medium">{{ $activeQueue->department->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Queue Number</p>
                        <p class="text-lg font-medium">{{ $activeQueue->queue_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Current Number</p>
                        <p class="text-lg font-medium">{{ $currentServing?->queue_number ?? 'Not started' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Joined At</p>
                        <p class="text-lg font-medium">{{ $activeQueue->joined_at->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-gray-500">Patients Ahead</p>
                        <p class="text-2xl font-semibold">{{ $patientsAhead }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-gray-500">Estimated Waiting Time</p>
                        <p class="text-2xl font-semibold">{{ $estimatedWait }} min</p>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('queue.show', $activeQueue) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">View Queue</a>
                    @if(in_array($activeQueue->status, ['waiting', 'serving'], true))
                        <form method="POST" action="{{ route('queue.cancel', $activeQueue) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Cancel Queue</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <p class="text-gray-600 mb-8">Choose the department you want to visit today.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($departments as $department)
                <div class="bg-white shadow rounded-xl p-6">
                    <h2 class="text-xl font-semibold">{{ $department->name }}</h2>
                    <p class="text-gray-500 mt-2">{{ $department->description }}</p>
                    <p class="text-gray-500 mt-2">Estimated consultation time: {{ $department->average_consultation_time }} minutes</p>
                    <a href="{{ route('queue.confirm', $department) }}" class="mt-5 block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">Join Queue</a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
