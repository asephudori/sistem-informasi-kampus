<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait Loggable
{
    protected function logActivity($name, $detail = null, $category = 'Default')
    {
        $request = app(Request::class);

        ActivityLog::create([
            'activity_name' => $name,
            'activity_detail' => $detail,
            'user_id' => Auth::id(),
            'times' => now(),
            'category' => $category,
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
        ]);
    }
}
