<?php
/**
 * Created by PhpStorm.
 * User: anhnguyen
 * Date: 06/10/2018
 * Time: 11:03
 */

namespace InApps\IAModules\Helpers;

use Illuminate\Support\Facades\Log;

class LogHelper
{
    static private $message;
    static private $context;

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @param boolean $supportELK
     *
     * @return void
     */
    public static function emergency($message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            $dba = debug_backtrace();
            $debugger = array_shift($dba);
            $content_array = ['file' => $debugger['file'], 'line' => $debugger['line'], 'content' => $context];
            self::generateLogContent($message, $content_array, $supportELK);
            Log::emergency(self::$message, self::$context);
        }
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @param boolean $supportELK
     *
     * @return void
     */
    public static function alert($message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            $dba = debug_backtrace();
            $debugger = array_shift($dba);
            $content_array = ['file' => $debugger['file'], 'line' => $debugger['line'], 'content' => $context];
            self::generateLogContent($message, $content_array, $supportELK);
            Log::alert(self::$message, self::$context);
        }
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @param boolean $supportELK
     *
     * @return void
     */
    public static function critical($message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            $dba = debug_backtrace();
            $debugger = array_shift($dba);
            $content_array = ['file' => $debugger['file'], 'line' => $debugger['line'], 'content' => $context];
            self::generateLogContent($message, $content_array, $supportELK);
            Log::critical(self::$message, self::$context);
        }
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @param boolean $supportELK
     *
     * @return void
     */
    public static function error($message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            $dba = debug_backtrace();
            $debugger = array_shift($dba);
            $content_array = ['file' => $debugger['file'], 'line' => $debugger['line'], 'content' => $context];
            self::generateLogContent($message, $content_array, $supportELK);
            Log::error(self::$message, self::$context);
        }
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @param boolean $supportELK
     *
     * @return void
     */
    public static function warning($message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            $dba = debug_backtrace();
            $debugger = array_shift($dba);
            $content_array = ['file' => $debugger['file'], 'line' => $debugger['line'], 'content' => $context];
            self::generateLogContent($message, $content_array, $supportELK);
            Log::warning(self::$message, self::$context);
        }
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @param boolean $supportELK
     *
     * @return void
     */
    public static function notice($message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            $dba = debug_backtrace();
            $debugger = array_shift($dba);
            $content_array = ['file' => $debugger['file'], 'line' => $debugger['line'], 'content' => $context];
            self::generateLogContent($message, $content_array, $supportELK);
            self::generateLogContent($message, $context, $supportELK);
            Log::notice(self::$message, self::$context);
        }
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @param boolean $supportELK
     *
     * @return void
     */
    public static function info($message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            self::generateLogContent($message, $context, $supportELK);
            Log::info(self::$message, self::$context);
        }
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @param boolean $supportELK
     *
     * @return void
     */
    public static function debug($message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            $dba = debug_backtrace();
            $debugger = array_shift($dba);
            $content_array = ['file' => $debugger['file'], 'line' => $debugger['line'], 'content' => $context];
            self::generateLogContent($message, $content_array, $supportELK);
            Log::debug(self::$message, self::$context);
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public static function log($level, $message, array $context = [], $supportELK = false)
    {
        $validateAttributes = self::validateLogAttribute($message, $context);
        if ($validateAttributes) {
            self::generateLogContent($message, $context, $supportELK);
            Log::log($level, self::$message, self::$context);
        }
    }

    /**
     * Private methods
     */
    /**
     * validateLogAttributes
     *
     * @param string $message
     * @param array $context
     *
     * @return bool
     */
    private static function validateLogAttribute(string $message, array $context)
    {
        $ret = true;
        if (empty($message) && empty($context)) {
            Log::critical('ERROR Write to log', ['content' => 'Nothing to write']);
            $ret = false;
        }
        return $ret;
    }

    /**
     * generateLogContent
     *
     * @param string $message
     * @param array $context
     * @param bool $supportELK
     *
     * @return void
     */
    private static function generateLogContent(string $message, array $context, bool $supportELK)
    {
        $context = is_array($context) ? $context : [$context];

        $ending = [
            'log_level' => BaseHeader::getLogLevel(),
            'trace_id' => BaseHeader::getTraceId(),
        ];
        $message = json_encode(array_merge(['name' => $message], $context, $ending));
        $context = [];

        self::$message = $message;
        self::$context = $context;
    }
}
