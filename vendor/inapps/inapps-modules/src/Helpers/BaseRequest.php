<?php
/**
 * Created by PhpStorm.
 * User: anhnguyen
 * Date: 05/03/2020
 * Time: 11:24.
 */

namespace InApps\IAModules\Helpers;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

class BaseRequest
{
    public $server;
    public $headers;
    // SERVER
    public $request_time_float;
    public $request_time;
    public $api_prefix;
    public $api_default_format;
    public $api_debug;
    // END SERVER

    // HEADERS
    public $rate_limit;
    public $rate_limit_remaining;
    public $client_ip;
    public $locale;
    public $host;
    public $full_url;
    public $method;
    public $content_type;
    public $port;
    public $request_url;
    public $encoding;
    public $query_string;
    public $protocal_version;

    public $version;

    public $dest;
    public $action;
    public $platform;
    public $user_id;
    public $unique;
    public $trusted_key = '';
    public $user_token = '';
    public $player_token = '';
    public $lang = 'en';
    public $request_params = [];

    public $trace_id = '';
    public $log_level = 0;

    public $ip = '';
    public $region_name = '';
    public $region_code = '';
    public $country_name = '';
    public $country_code = '';
    public $city_name = '';

    public $benchmark_api = 0;
    public $benchmark_app = 0;
    public $benchmark_query = 0;
    public $benchmark_redis = 0;
    public $execution_query = 0;

    // DEBUGGING
    public $debug_mode = 0;

    // END HEADERS

    public function __construct($request)
    {
        $this->initialize($request);
    }

    public function initialize(Request $request)
    {
        $this->server = $request->server;
        $this->headers = $request->headers;

        $this->client_ip = $request->getClientIp();
        $this->locale = $request->getLocale();
        $this->host = $request->getHost();
        $this->full_url = $request->getRequestUri();
        $this->method = $request->getMethod();
        $this->content_type = $request->getContentType();
        $this->port = $request->getPort();
        $this->request_url = $request->getRequestUri();
        $this->encoding = $request->getEncodings();
        $this->query_string = $request->getQueryString();
        $this->protocal_version = $request->getProtocolVersion();
        $this->request_params = $request->all();

        $user_info = $request->get('user_info');
        $this->user_id = empty($user_info) ? '' : $user_info['user_id'];

        $this->setServers($this->server->all());
        $this->setHeaders($this->headers);
    }

    protected function setServers($server)
    {
        if (isset($server['REQUEST_TIME_FLOAT'])) {
            $this->request_time_float = $server['REQUEST_TIME_FLOAT'];
        }
        if (isset($server['REQUEST_TIME'])) {
            $this->request_time = $server['REQUEST_TIME'];
        }
        if (isset($server['API_PREFIX'])) {
            $this->api_prefix = $server['API_PREFIX'];
        }
        if (isset($server['API_DEFAULT_FORMAT'])) {
            $this->api_default_format = $server['API_DEFAULT_FORMAT'];
        }
        if (isset($server['API_DEBUG'])) {
            $this->api_debug = $server['API_DEBUG'];
        }
    }

    protected function setHeaders(HeaderBag $headers)
    {
        if (!empty($headers->get('x-ratelimit-limit'))) {
            $this->rate_limit = $headers->get('x-ratelimit-limit');
        }

        if (!empty($headers->get('x-ratelimit-remaining'))) {
            $this->rate_limit_remaining = $headers->get('x-ratelimit-remaining');
        }

        if (!empty($headers->get('version'))) {
            $this->version = $headers->get('version');
        }

        if (!empty($headers->get('dest'))) {
            $this->dest = $headers->get('dest');
        }

        if (!empty($headers->get('action'))) {
            $this->action = $headers->get('action');
        }

        if (!empty($headers->get('platform'))) {
            $this->platform = $headers->get('platform');
        }

        if (!empty($headers->get('unique'))) {
            $this->unique = $headers->get('unique');
        }

        if (!empty($headers->get('trusted-key'))) {
            $this->trusted_key = $headers->get('trusted-key');
        }

        if (!empty($headers->get('user-token'))) {
            $this->user_token = $headers->get('user-token');
        }

        if (!empty($headers->get('player-token'))) {
            // Player token is over-written as user_token
            // $this->user_token = $headers->get('player-token');

            // the above was commented to support legacy login & registration
            $this->player_token = $headers->get('player-token');
        }

        if (!empty($headers->get('lang'))) {
            $this->lang = $headers->get('lang');
        }

        if (!empty($headers->get('trace-id'))) {
            $this->trace_id = $headers->get('trace-id');
        }

        if (!empty($headers->get('log-level'))) {
            $this->log_level = $headers->get('log-level');
        }

        if (!empty($headers->get('sourceip'))) {
            $this->ip = $headers->get('sourceip');
        }

        if (!empty($headers->get('sourceregionname'))) {
            $this->region_name = $headers->get('sourceregionname');
        }

        if (!empty($headers->get('sourceregioncode'))) {
            $this->region_code = $headers->get('sourceregioncode');
        }

        if (!empty($headers->get('sourcecountryname'))) {
            $this->country_name = $headers->get('sourcecountryname');
        }

        if (!empty($headers->get('sourcecountrycode'))) {
            $this->country_code = $headers->get('sourcecountrycode');
        }

        if (!empty($headers->get('sourcecityname'))) {
            $this->city_name = $headers->get('sourcecityname');
        }

        if (!empty($headers->get('benchmark-api'))) {
            $this->benchmark_app = $headers->get('benchmark-api');
        }

        if (!empty($headers->get('benchmark-app'))) {
            $this->benchmark_app = $headers->get('benchmark-app');
        }

        if (!empty($headers->get('benchmark-query'))) {
            $this->benchmark_query = $headers->get('benchmark-query');
        }

        if (!empty($headers->get('benchmark-redis'))) {
            $this->benchmark_redis = $headers->get('benchmark-redis');
        }

        if (!empty($headers->get('execution-query'))) {
            $this->execution_query = $headers->get('execution-query');
        }

        if (!empty($headers->get('debug-mode'))) {
            $this->debug_mode = $headers->get('debug-mode');
        }
    }
}