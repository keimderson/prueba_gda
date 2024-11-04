<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {

        $ip = $request->ip();

        Log::channel('apirest')->info('Incoming request', [
            'ip' => $ip,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'payload' => $request->all(),
        ]);

        $response = $next($request);

        if (env('LOGGING_ENABLED') !== false) {
            Log::channel('apirest')->info('Outgoing response', [
                'ip' => $ip,
                'status' => $response->status(),
                'content' => $response->getContent(),
            ]);
        }

        return $response;
    }
}
