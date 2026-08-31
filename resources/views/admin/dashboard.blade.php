<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold">Admin Dashboard</h1>
                <p class="text-gray-500 mt-1">Manage departments and review queue activity.</p>
            </div>
            <a href="{{ route('departments.index') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Manage Departments</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 mb-10">
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200"><p class="text-sm text-gray-500">Waiting</p><p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['waiting'] }}</p></div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200"><p class="text-sm text-gray-500">Serving</p><p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['serving'] }}</p></div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200"><p class="text-sm text-gray-500">Completed</p><p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['completed'] }}</p></div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200"><p class="text-sm text-gray-500">Skipped</p><p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['skipped'] }}</p></div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200"><p class="text-sm text-gray-500">Cancelled</p><p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['cancelled'] }}</p></div>
            <div class="rounded-xl bg-white p-6 shadow border border-gray-200"><p class="text-sm text-gray-500">Total</p><p class="mt-3 text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</p></div>
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
                    <option value="waiting" @selected(request('status') == 'waiting')>Waiting</option>
                    <option value="serving" @selected(request('status') == 'serving')>Serving</option>
                    <option value="skipped" @selected(request('status') == 'skipped')>Skipped</option>
                    <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
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
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No queues for the selected range.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $queuesToday->links() }}</div>
    </div>
</x-app-layout>