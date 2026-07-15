<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display activity log lists.
     */
    public function index()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(20);

        // Aggregate log counts for visual chart
        $analytics = ActivityLog::select('action', \DB::raw('count(*) as total'))
            ->groupBy('action')
            ->orderBy('total', 'desc')
            ->take(5)
            ->pluck('total', 'action')
            ->all();

        return view('logs.index', compact('logs', 'analytics'));
    }
}
