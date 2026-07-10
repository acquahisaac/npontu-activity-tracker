<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Activity Log') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
            @endif

            {{-- Date picker to view/update a specific day --}}
            <form method="GET" action="{{ route('logs.index') }}" class="mb-4 flex items-center gap-3">
                <label for="date" class="text-sm font-medium text-gray-700">Viewing date:</label>
                <input type="date" name="date" id="date" value="{{ $date }}" class="border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($activities->isEmpty())
                <p class="text-gray-500">
                    No activities defined yet. <a href="{{ route('activities.create') }}" class="text-indigo-600 underline">Add one first.</a>
                </p>
                @else
                <div class="space-y-6">
                    @foreach ($activities as $activity)
                    @php $log = $logs->get($activity->id); @endphp

                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                  <div>
                    <div class="flex items-center gap-2">
                     <h3 class="font-semibold text-gray-800">{{ $activity->name }}</h3>
                        @if (!$log && $staleActivityIds->contains($activity->id))
                         <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-medium">
                       Carried over from previous day
                </span>
            @endif
        </div>

                            @if ($log)
                            <span class="text-xs px-2 py-1 rounded-full
                                            {{ $log->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($log->status) }}
                            </span>
                            @else
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                Not updated
                            </span>
                            @endif
                        </div>

                        {{-- Show who last updated it and when --}}
                        @if ($log)
                        <p class="text-xs text-gray-400 mb-3">
                            Last updated by <span class="font-medium">{{ $log->user->name }}</span>
                            on {{ $log->updated_at->format('d M Y, H:i') }}
                        </p>
                        @endif

                        <form method="POST" action="{{ route('logs.store') }}" class="flex flex-wrap gap-3 items-end">
                            @csrf
                            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                            <input type="hidden" name="log_date" value="{{ $date }}">

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status" class="border-gray-300 rounded-md text-sm">
                                    <option value="pending" {{ ($log?->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="done" {{ ($log?->status ?? '') === 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Remark</label>
                                <input type="text" name="remark" value="{{ $log->remark ?? '' }}" class="w-full border-gray-300 rounded-md text-sm" placeholder="Add a note...">
                            </div>

                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                                Update
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
