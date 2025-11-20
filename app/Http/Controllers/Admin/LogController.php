<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = BusLog::with(['bus', 'trip', 'user'])
            ->latest('log_time')
            ->paginate(15);

        return view('admin.logs.index', compact('logs'));
    }
}
