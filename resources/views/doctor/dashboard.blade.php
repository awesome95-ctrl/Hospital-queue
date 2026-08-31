<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Doctor Queue Board</h1>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">{{ session('error') }}</div>
        @endif

        <div class="mb-8 overflow-hidden rounded-xl border border-gray-200 bg-white shadow">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="text-xl font-semibold">Currently Assigned</h2>
            </div>
            @if($servedQueues->isEmpty())
                <div class="px-6 py-12 text-center text-gray-500">No patients are currently assigned to you.</div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($servedQueues as $queue)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $queue->queue_number }}</div>
                                <div class="text-sm text-gray-600">{{ $queue->user->name }} • {{ $queue->department->name }}</div>
                            </div>
                            <form method="POST" action="{{ route('doctor.complete', $queue) }}">
                                @csrf
                                <button type="submit" class="rounded-lg bg-green-600 px-3 py-2 text-white hover:bg-green-700">Complete Consultation</button>
                            </form>
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
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($waitingQueues as $queue)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $queue->queue_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->department->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->joined_at->format('h:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 capitalize">{{ $queue->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">No waiting patients found for today.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>