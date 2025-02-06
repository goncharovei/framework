<?php

use Carbon\Carbon;
use Foundation\Configuration\Env;
use Foundation\Kernels\Http\Response;
use Foundation\Kernels\Http\View;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use PHPMailer\PHPMailer\PHPMailer;

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @template TClass
     *
     * @param string|class-string<TClass>|null $abstract
     * @param array $parameters
     * @return ($abstract is class-string<TClass> ? TClass : ($abstract is null ? Foundation\Application : mixed))
     * @throws BindingResolutionException
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return app()->path($path);
    }
}

if (! function_exists('url')) {

    function url(string $path = '', array $params = [])
    {
        return app('url')->generate($path, $params);
    }
}

if (! function_exists('asset')) {

    function asset(string $path, array $params = [])
    {
        return app('url')->asset($path, $params);
    }
}

if (! function_exists('route')) {

    function route(string $name, array $params = [])
    {
        return app('url')->route($name, $params);
    }
}

if (! function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath($path);
    }
}

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array<string, mixed>|string|null  $key
     * @param  mixed  $default
     * @return ($key is null ? \Illuminate\Config\Repository : ($key is string ? mixed : null))
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        return Env::get($key, $default);
    }
}

if (! function_exists('join_paths')) {
    function join_paths($basePath, ...$paths)
    {
        foreach ($paths as $index => $path) {
            if (empty($path) && $path !== '0') {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath . implode('', $paths);
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->configPath($path);
    }
}

if (! function_exists('error')) {
    /**
     * Write some information to the log.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    function error($message, $context = [])
    {
        app('log')->error($message, $context);
    }
}

if (! function_exists('router')) {

    /**
     * @throws BindingResolutionException
     */
    function router()
    {
        return app('router');
    }
}

if (! function_exists('request')) {

    /**
     * @throws BindingResolutionException
     */
    function request()
    {
        return app('request');
    }
}

if (! function_exists('response')) {

    /**
     * @throws BindingResolutionException
     */
    function response($content = null, $status = 200, array $headers = [], string $reason = '')
    {
        return app('response')->setContent($content)
            ->withStatus($status, $reason)->setHeaders($headers);
    }
}

if (! function_exists('view')) {

    /**
     * @throws BindingResolutionException
     */
    function view($view = null, $data = []): Response|View
    {
        $instance = app('view');
        if (func_num_args() === 0) {
            return $instance;
        }

        return $instance->response($view . '.html.twig', $data);
    }
}

if (! function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string|null  $message
     * @param  array  $context
     * @return ($message is null ? Psr\Log\LoggerInterface : null)
     */
    function logger($message = null, array $context = [])
    {
        if (is_null($message)) {
            return app('log');
        }

        return app('log')->debug($message, $context);
    }
}

if (! function_exists('mailer')) {
    /**
     * Send a message by email
     *
     * @return PHPMailer
     */
    function mailer()
    {
        return app('mailer');
    }
}

if (! function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param  \DateTimeZone|string|null  $tz
     */
    function now($tz = null): Carbon
    {
        return Carbon::now($tz);
    }
}

if (! function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return app()->publicPath($path);
    }
}

if (! function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @template TClass
     *
     * @param  string|class-string<TClass>  $name
     * @param  array  $parameters
     * @return ($name is class-string<TClass> ? TClass : mixed)
     */
    function resolve($name, array $parameters = [])
    {
        return app($name, $parameters);
    }
}

if (! function_exists('resource_path')) {
    /**
     * Get the path to the resources' folder.
     *
     * @param  string  $path
     * @return string
     */
    function resource_path($path = '')
    {
        return app()->resourcePath($path);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function storage_path($path = '')
    {
        return app()->storagePath($path);
    }
}

if (! function_exists('today')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param  \DateTimeZone|string|null  $tz
     */
    function today($tz = null): Carbon
    {
        return Carbon::today($tz);
    }
}
