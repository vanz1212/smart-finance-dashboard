<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next, string $action, ?string $pageKey = null, ?string $pageLabel = null): Response
    {
        $response = $next($request);

        ActivityLog::create([
            'user_id' => optional($request->user())->id,
            'action' => $action,
            'page_key' => $pageKey,
            'page_label' => $pageLabel,
            'route_name' => optional($request->route())->getName(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
        ]);

        return $response;
    }
}
