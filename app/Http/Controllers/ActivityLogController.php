<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Require admin role
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $logs = Activity::with('causer')->latest()->paginate(50);
        
        return view('activity-logs.index', compact('logs'));
    }
}
