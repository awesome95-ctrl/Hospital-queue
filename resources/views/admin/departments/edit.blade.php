<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-6">
        <div class="bg-white rounded-xl shadow p-8">
            <h1 class="text-2xl font-bold mb-4">Edit Department</h1>

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('departments.update', $department) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name', $department->name) }}" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Code</label>
                    <input type="text" name="code" value="{{ old('code', $department->code) }}" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="4" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $department->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Average Consultation Time (minutes)</label>
                    <input type="number" name="average_consultation_time" value="{{ old('average_consultation_time', $department->average_consultation_time) }}" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="1" required>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('departments.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">Back</a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Update Department</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>