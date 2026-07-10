<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500">Total Activities</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalActivities }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Done Today</p>
                    <p class="text-3xl font-bold text-green-600">{{ $doneToday }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500">Pending Today</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pendingToday }}</p>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="bg-white rounded-lg shadow-sm p-6 flex flex-wrap gap-3">
                <a href="{{ route('logs.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                    Go to Daily Log
                </a>
                <a href="{{ route('activities.create') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50">
                    + Add Activity
                </a>
                <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50">
                    View Reports
                </a>
            </div>

            {{-- Recent activity today --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Recent Updates Today</h3>
                @if ($recentLogs->isEmpty())
                    <p class="text-gray-500 text-sm">No updates yet today.</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($recentLogs as $log)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $log->activity->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $log->user->name }} · {{ $log->updated_at->format('H:i') }}
                                    </p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $log->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
