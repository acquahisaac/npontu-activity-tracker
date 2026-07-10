<?php
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $today = \Carbon\Carbon::today()->toDateString();

    $totalActivities = \App\Models\Activity::count();
    $doneToday = \App\Models\ActivityLog::whereDate('log_date', $today)->where('status', 'done')->count();
    $pendingToday = $totalActivities - $doneToday;

    $recentLogs = \App\Models\ActivityLog::with(['activity', 'user'])
        ->whereDate('log_date', $today)
        ->latest('updated_at')
        ->take(5)
        ->get();

    return view('dashboard', compact('totalActivities', 'doneToday', 'pendingToday', 'recentLogs'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('activities', ActivityController::class)->only(['index', 'create', 'store']);
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::post('/logs', [ActivityLogController::class, 'store'])->name('logs.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';
