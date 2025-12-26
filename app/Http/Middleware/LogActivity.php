<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log the activity after the request has been processed
        if (auth()->check()) {
            ActivityLog::log(
                action: $request->method().' '.$request->path(),
                properties: [
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'status_code' => $response->getStatusCode(),
                ]
            );
        }

        return $response;
    }
}
