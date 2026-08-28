<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LogAdminActivity
{
    /**
     * Log mutating admin requests (POST/PUT/PATCH/DELETE) as an audit trail.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $response;
        }

        if (! Auth::check()) {
            return $response;
        }

        $route = $request->route();
        $action = $route ? $route->getActionMethod() : strtolower($request->method());
        $routeName = $route ? $route->getName() : null;

        $subjectType = null;
        $subjectId = null;

        if ($route) {
            foreach ($route->parameters() as $param) {
                if ($param instanceof \Illuminate\Database\Eloquent\Model) {
                    $subjectType = get_class($param);
                    $subjectId = $param->getKey();
                    break;
                }
            }
        }

        Activity::create([
            'user_id' => Auth::id(),
            'type' => $action,
            'description' => 'Admin melakukan ' . Str::replace('_', ' ', $action)
                . ($routeName ? ' (' . $routeName . ')' : ''),
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'route' => $routeName,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'method' => $request->method(),
                'path' => $request->path(),
            ],
        ]);

        return $response;
    }
}
