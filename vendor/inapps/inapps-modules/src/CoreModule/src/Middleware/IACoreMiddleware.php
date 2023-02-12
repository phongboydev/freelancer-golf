<?php

namespace Modules\IACoreModule\Middleware;

use Closure;

class IACoreMiddleware
{
    public function handle($request, Closure $next)
    {
        echo "<pre>";
        echo "I am loaded in Middleware";
        echo "</pre>";
        // Perform action
        return $next($request);
    }
}
