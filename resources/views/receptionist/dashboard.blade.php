<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold">Reception Desk</h1>
                <p class="text-gray-500 mt-1">Manage active and waiting patients.</p>
            </div>
        </div>

        <form method="GET" class="mb-6 grid gap-4 md:grid-cols-4 rounded-xl border border-gray-200 bg-white p-4 shadow">
            <div>
                <label class="block text-sm font-medium text-gray-700">Department</label>
                <select name="department_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">All</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">All</option>
                    <option value="waiting" @selected(request('status') === 'waiting')>Waiting</option>
                    <option value="serving" @selected(request('status') === 'serving')>Serving</option>
                    <option value="skipped" @selected(request('status') === 'skipped')>Skipped</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Filter</button>
            </div>
        </form>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">{{ session('error') }}</div>
        @endif

        <div class="mb-8 overflow-hidden rounded-xl border border-gray-200 bg-white shadow">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold">Currently Serving</h2>
            </div>
            @if($currentQueues->isEmpty())
                <div class="px-6 py-12 text-center text-gray-500">No patient is currently being served.</div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($currentQueues as $queue)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $queue->queue_number }}</div>
                                <div class="text-sm text-gray-600">{{ $queue->user->name }} • {{ $queue->department->name }}</div>
                            </div>
                            <div class="text-right text-sm text-gray-600">
                                <div>Doctor: {{ $queue->doctor?->name ?? 'Unassigned' }}</div>
                                <div>Called: {{ $queue->called_at?->format('h:i A') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Queue</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Wait Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($queues as $queue)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $queue->queue_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->department->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 capitalize">{{ $queue->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @php($wait = app(App\Services\QueueWaitTimeService::class)->estimatedWaitingMinutes($queue))
                            {{ $wait }} min
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 space-x-2">
                            @if($queue->status === 'waiting')
                                <form method="POST" action="{{ route('receptionist.call', $queue) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">Call Next</button>
                                </form>
                                <form method="POST" action="{{ route('receptionist.skip', $queue) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-yellow-500 px-3 py-2 text-white hover:bg-yellow-600">Skip</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No queue records found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $queues->links() }}
        </div>
    </div>
</x-app-layout>
