<?php

namespace InApps\IAModules;

use Closure;
use InApps\IAModules\Helpers\BaseHeader;
use InApps\IAModules\Helpers\BaseHelper;
use InApps\IAModules\Helpers\BaseRequest;
use InApps\IAModules\Helpers\LogHelper;


class BaseMiddleware
{
    public $response;

    public function handle($request, Closure $next)
    {
        $request_info = new BaseRequest($request);

        // set locale
        app('translator')->setLocale($request_info->lang);
        // predefined data
        $request->attributes->add(['lang' => $request_info->lang]);

        if (empty($request_info->trace_id)) {
            BaseHeader::generateTraceId();
        } else {
            BaseHeader::setTraceId($request_info->trace_id);
        }
        BaseHeader::setLogLevel($request_info->log_level + 1);

        BaseHeader::setDataArray(['debug-mode' => $request_info->debug_mode]);

        //Controller action and memory usage
        $origin_memory_usage = memory_get_usage();
        $this->response = $next($request);
        $end_memory_usage = memory_get_usage();
        $this->response->headers->set(
            'memory-usage',
            BaseHelper::explainMemoryUsage($origin_memory_usage, $end_memory_usage)
        );

        // Set controller & action headers
        $route = BaseHelper::getRoute($request);
        $this->response->headers->set('controller', isset($route['controller']) ? $route['controller'] : '');
        $this->response->headers->set('action', isset($route['action']) ? $route['action'] : '');
        BaseHelper::writeRequestLog($request_info, $this->response);

        // Set trace id and log level to output headers
        $this->response->headers->set('trace-id', BaseHeader::getTraceId());
        $this->response->headers->set('log-level', BaseHeader::getLogLevel());

        if (BaseHeader::getDataByKey('debug-mode')) {
            $content['request'] = json_encode(($request_info->request_params));
            $content['response'] = $this->response->body();
            LogHelper::debug('--DEBUG--', $content);
        }

        return $this->response;
    }
}
