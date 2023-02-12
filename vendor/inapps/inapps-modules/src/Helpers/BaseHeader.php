<?php
/**
 * Created by PhpStorm.
 * User: anhnguyen
 * Date: 05/03/2020
 * Time: 11:24.
 */

namespace InApps\IAModules\Helpers;

class BaseHeader
{
    private static $trace_id;
    private static $log_level;
    private static $request_ip_info = [];
    private static $array_data = [];
    private static $microtime_start;

    public function __construct($trace_id)
    {
        self::$trace_id = $trace_id;
    }

    /** Set and Get Trace Id and Log Level for log */

    /**
     * @param $trace_id
     */
    public static function setTraceId($trace_id)
    {
        self::$trace_id = $trace_id;
    }

    public static function generateTraceId()
    {
        $random_number = rand(1, 99999);
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        // random uppercase string with 8 Characters
        for ($i = 0; $i < 8; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $trace_id = $randomString.'-'.$random_number;
        self::$trace_id = $trace_id;
    }

    /**
     * @return mixed
     */
    public static function getTraceId()
    {
        return self::$trace_id;
    }

    /** Log Level*/

    /**
     * @param $log_level
     */
    public static function setLogLevel($log_level)
    {
        self::$log_level = $log_level;
    }

    /**
     * @return mixed
     */
    public static function getLogLevel()
    {
        return self::$log_level;
    }

    /** Set and Get Geo Location Information from infrastructure level */

    /**
     * @param $request_ip_info
     */
    public static function setRequestIpInfo($request_ip_info)
    {
        self::$request_ip_info = $request_ip_info;
    }

    /**
     * @return mixed
     */
    public static function getRequestIpInfo()
    {
        return json_decode(json_encode(self::$request_ip_info));
    }

    /**
     * @param string $key
     * @param $value
     */
    public static function setData(string $key, $value)
    {
        self::setDataArray([$key => $value]);
    }

    /**
     * @param array $array
     */
    public static function setDataArray(array $array)
    {
        self::$array_data = array_merge(self::$array_data, $array);
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public static function getDataByKey(string $key)
    {
        $data_array = self::$array_data;
        if (isset($data_array[$key])) {
            return $data_array[$key];
        } else {
            return false;
        }
    }

    /**
     * timerStart
     * @return mixed
     */
    public static function timerStart()
    {
        self::$microtime_start = microtime(true);
        return self::$microtime_start;
    }

    /**
     * @param null $key
     * @return mixed
     */
    public static function timerStop($key = null)
    {
        $end = microtime(true);

        $time = $end - self::$microtime_start;
        if (!empty($key)) {
            $data_array = self::$array_data;
            if (!empty($data_array[$key])) {
                $data_array[$key] += $time;
            } else {
                $data_array[$key] = $time;
            }
            self::$array_data = $data_array;
        }
        return $time;
    }
}
