<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    // Daily dashboard: shows today's activities + lets personnel update status
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $activities = Activity::all();

        $logs = ActivityLog::with('user')
            ->whereDate('log_date', $date)
            ->get()
            ->keyBy('activity_id');

        // Find activities whose most recent prior log was still "pending"
        $staleActivityIds = ActivityLog::where('status', 'pending')
            ->whereDate('log_date', '<', $date)
            ->whereIn('activity_id', $activities->pluck('id'))
            ->orderBy('log_date', 'desc')
            ->get()
            ->unique('activity_id')
            ->pluck('activity_id');

        return view('logs.index', compact('activities', 'logs', 'date', 'staleActivityIds'));
    }

    // Save or update a status/remark for a given activity on a given date
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'log_date' => 'required|date',
            'status' => 'required|in:pending,done',
            'remark' => 'nullable|string',
        ]);

        ActivityLog::updateOrCreate(
            [
                'activity_id' => $validated['activity_id'],
                'log_date' => $validated['log_date'],
            ],
            [
                'user_id' => auth()->id(),
                'status' => $validated['status'],
                'remark' => $validated['remark'] ?? null,
            ]
        );

        return redirect()->route('logs.index', ['date' => $validated['log_date']])
            ->with('success', 'Activity updated successfully.');
    }
}