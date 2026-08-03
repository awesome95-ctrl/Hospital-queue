<x-app-layout>

<div class="max-w-3xl mx-auto py-10">

    <div class="bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-3xl font-bold mb-4">
            {{ $department->name }}
        </h1>

        <p class="text-gray-600">
            {{ $department->description }}
        </p>

        <hr class="my-6">

        <div class="space-y-3">

            <p>
                <strong>Average Consultation Time:</strong>
                {{ $department->average_consultation_time }} minutes
            </p>

            <p>
                Please arrive before your turn.
            </p>

            <p>
                Missing your turn may cause your queue to be skipped.
            </p>

        </div>

        <form
            action="{{ route('queue.join', $department) }}"
            method="POST"
            class="mt-8">

            @csrf

            <button
                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg">

                Confirm & Join Queue

            </button>

        </form>

    </div>

</div>

</x-app-layout>