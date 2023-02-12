<?php

namespace App\Http\Middleware;

use App\Tasks\Admin\RoleTask;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Constants\HttpStatus;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (Auth::guard($guard)->guest()) {
            return redirect('admin/login');
        } else {
            $admin_info = Auth::guard('admin')->user();
            $user_role = app(RoleTask::class)->getRoleDetailByAdminId($admin_info->role_id);
            if ($user_role) {
                $request->admin_info = $admin_info;
                $request->user_role = $user_role;
            } else {
                return Response::json('This user has no role', HttpStatus::UNAUTHORIZED);
            }
            return $next($request);
        }
    }
}
