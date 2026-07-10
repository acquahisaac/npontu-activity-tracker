<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $logs = collect(); // empty by default until a range is submitted

        if ($startDate && $endDate) {
            $logs = ActivityLog::with(['activity', 'user'])
                ->whereBetween('log_date', [$startDate, $endDate])
               -> orderBy('log_date', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        return view('reports.index', compact('logs', 'startDate', 'endDate'));
    }
}