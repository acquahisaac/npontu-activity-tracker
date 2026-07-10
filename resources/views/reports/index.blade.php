<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity History Report') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" action="{{ route('reports.index') }}" class="mb-6 bg-white shadow-sm sm:rounded-lg p-6 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 border-gray-300 rounded-md shadow-sm" required>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (!$startDate || !$endDate)
                <p class="text-gray-500">Select a date range above to view activity history.</p>
                @elseif ($logs->isEmpty())
                <p class="text-gray-500">No activity logs found between {{ $startDate }} and {{ $endDate }}.</p>
                @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Activity</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Remark</th>
                            <th class="px-4 py-2">Updated By</th>
                            <th class="px-4 py-2">Updated At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($logs as $log)
                        <tr>
                            <td class="px-4 py-3">{{ $log->log_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-medium">{{ $log->activity->name }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full
                                            {{ $log->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->remark ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->user->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
