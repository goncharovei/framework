<?php

namespace Foundation\Kernels\Http;

/**
 * @mixin \League\Route\Router
 */
class Router
{
    public static function __callStatic(string $method, array $parameters): mixed
    {
        return call_user_func_array(array(router(), $method), $parameters);
    }
}