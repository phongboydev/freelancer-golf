<?php

namespace App\Http\Middleware;

use App\Constants\BaseConstants;
use App\Providers\RouteServiceProvider;
use App\Tasks\Admin\RoleTask;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module, ...$permissions)
    {
        $checkPermission = app(RoleTask::class)
            ->checkPermission($module, $permissions, request()->user_role);
        if (!$checkPermission) {
            abort(403);
        }
        return $next($request);
    }
}
