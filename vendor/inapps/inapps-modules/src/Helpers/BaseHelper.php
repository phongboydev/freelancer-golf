<?php
/**
 * Created by PhpStorm.
 * User: anhnguyen
 * Date: 06/10/2018
 * Time: 12:09
 */

namespace InApps\IAModules\Helpers;

class BaseHelper
{
    /**
     * @param $request
     * @param $response
     */
    public static function writeRequestLog($request, $response)
    {
        $context = [
            'source' => $request->client_ip,
            'dest' => $request->dest,
            'method' => $request->method,
            'controller' => $response->headers->get('controller'),
            'action' => $response->headers->get('action'),
            'userId' => $request->user_id,
            'platform' => (empty($request->platform)) ? '' : $request->platform,
            'execution_time' => 0,
            'locale' => $request->locale,
            'response_code' => $response->getStatusCode(),
            'trace_id' => BaseHeader::getTraceId(),
            'log_level' => BaseHeader::getLogLevel(),
            'version' => $response->headers->get('version'),
            'trustedKey' => (empty($request->trusted_key)) ? '' : $request->trusted_key,
            'jwt' => (empty($request->jwt)) ? '' : $request->jwt,
            'memoryUsage' => $response->headers->get('memory-usage'),
            'bodySize' => '',
        ];

        $supportELK = false;

        if ($context['response_code'] >= 300) {
            $supportELK = true;
        }

        $execution_time = (microtime(true) - $request->request_time_float) * 1000;
        $context['execution_time'] = $execution_time . ' ms';
        if ($execution_time > 500) {
            $context['exceed_exec_time'] = 'TRUE';
            $context['request_params'] = $request->request_params;
        }
        LogHelper::info('REQUEST LOG:', $context, $supportELK);
    }

    public static function explainMemoryUsage($start, $end)
    {
        $mem_usage = $end - $start;
        $ret = '';

        if ($mem_usage < 1024) {
            $ret = $mem_usage . " bytes";
        } elseif ($mem_usage < 1048576) {
            $ret = round($mem_usage / 1024, 2) . " Kbs";
        } else {
            $ret = round($mem_usage / 1048576, 2) . " MB";
        }

        return $ret;
    }

    /**
     * @param $request
     * @return mixed
     */
    public static function getRoute($request)
    {
        $ret['controller'] = null;
        $ret['action'] = null;
        $route = $request->route();
        if (!empty($route->action['uses'])) {
            $arrRoutes = explode('@', $route->action['uses']);
            $arrPath = explode('\\', $arrRoutes[0]);
            $ret['controller'] = $arrPath[count($arrPath) - 1];
            $ret['action'] = $arrRoutes[1];
        }
        return $ret;
    }

    public static function arrayMSort($array, $cols, $reindex = true)
    {
        $col_arr = array();
        foreach ($cols as $col => $order) {
            $col_arr[$col] = array();
            foreach ($array as $k => $row) {
                $col_arr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$col_arr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($col_arr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) {
                    $ret[$k] = $array[$k];
                }
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        if ($reindex) {
            return array_values($ret);
        }
        return $ret;
    }
}
