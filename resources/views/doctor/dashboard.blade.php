<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-bold mb-6">Doctor Queue Board</h1>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Queue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($waitingQueues as $queue)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $queue->queue_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->department->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->joined_at->format('h:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 space-x-2">
                                <form method="POST" action="{{ route('doctor.call', $queue) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">Call Next</button>
                                </form>
                                <form method="POST" action="{{ route('doctor.skip', $queue) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-yellow-500 px-3 py-2 text-white hover:bg-yellow-600">Skip</button>
                                </form>
                                <form method="POST" action="{{ route('doctor.complete', $queue) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-green-600 px-3 py-2 text-white hover:bg-green-700">Complete</button>
                                </form>
                            </td>
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