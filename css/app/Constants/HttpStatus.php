<?php

namespace App\Constants;

class HttpStatus
{
    /**
     * public constant variables
     */
    public const MUST_DEFINE = 0;
    // 1xx Informational
    public const CONTINUE = 100;
    // 2xx Success
    public const OK = 200;
    public const CREATED = 201;
    public const NO_CONTENT = 204;
    // 3xx Redirection
    public const NOT_MODIFIED = 304;
    // 4xx Client Error
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const CONFLICT = 409;
    public const PRECONDITION_FAILED = 412;
    public const UNSUPPORTED_MEDIA_TYPE = 415;
    public const UNPROCESSABLE = 422;

    // 5xx Server Error
    public const INTERNAL_ERROR = 500;
    public const NOT_IMPLEMENTED = 501;

    public const ERROR_ARRAY = array(
        self::BAD_REQUEST,
        self::UNAUTHORIZED,
        self::FORBIDDEN,
        self::NOT_FOUND,
        self::HTTP_METHOD_NOT_ALLOWED,
        self::CONFLICT,
        self::PRECONDITION_FAILED,
        self::UNSUPPORTED_MEDIA_TYPE,
        self::INTERNAL_ERROR,
        self::NOT_IMPLEMENTED,
        self::UNPROCESSABLE
    );
}
