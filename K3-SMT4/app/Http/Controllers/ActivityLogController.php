<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->user_id) $query->where('user_id', $request->user_id);
        if ($request->module) $query->where('module', $request->module);
        if ($request->action) $query->where('action', 'ilike', "%{$request->action}%");
        if ($request->date_from) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('created_at', '<=', $request->date_to);

        $logs = $query->with('user')->latest('created_at')->paginate(25)->withQueryString();

        $modules = ActivityLog::select('module')->distinct()->pluck('module')->sort();
        $actions = ActivityLog::select('action')->distinct()->pluck('action')->sort();

        return view('activity-logs.index', compact('logs', 'modules', 'actions'));
    }
}