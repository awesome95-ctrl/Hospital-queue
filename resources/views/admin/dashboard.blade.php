<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold">Admin Dashboard</h1>
                <p class="text-gray-500 mt-1">Manage departments and review today's queue activity.</p>
            </div>
            <a href="{{ route('departments.index') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Manage Departments</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 mb-10">
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200">
                <p class="text-sm text-gray-500">Waiting</p>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['waiting'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200">
                <p class="text-sm text-gray-500">Serving</p>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['serving'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200">
                <p class="text-sm text-gray-500">Completed</p>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['completed'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200">
                <p class="text-sm text-gray-500">Skipped</p>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['skipped'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200">
                <p class="text-sm text-gray-500">Cancelled</p>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['cancelled'] }}</p>
            </div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200">
                <p class="text-sm text-gray-500">Total Today</p>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Queue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($queuesToday as $queue)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $queue->queue_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->department->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 capitalize">{{ $queue->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $queue->joined_at->format('h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">No queues for today.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>