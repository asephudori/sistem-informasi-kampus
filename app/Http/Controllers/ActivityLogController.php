<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');

        $logs = ActivityLog::when($startDate, function ($query, $startDate) {
            return $query->where('times', '>=', $startDate);
        })
        ->when($endDate, function ($query, $endDate) {
            return $query->where('times', '<=', $endDate);
        })
        ->when($category, function ($query, $category) {
            return $query->where('category', $category);
        })
        ->with('user')
        ->orderBy('times', 'desc')
        ->paginate(10);

        return response()->json($logs);
    }
}