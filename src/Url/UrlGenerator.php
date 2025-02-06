<?php

namespace Foundation\Url;

use Foundation\Kernels\Http\Router;

class UrlGenerator
{
    private string $baseURL;

    public function __construct(string $baseURL)
    {
        if (!$this->isValidUrl($baseURL))
        {
            throw new \InvalidArgumentException('Base url is incorrect');
        }

        $this->baseURL = rtrim($baseURL, '/');
    }

    public function asset(string $path, array $params = []): string
    {
        return $this->generate('asset' . '/' . ltrim($path, '/'), $params);
    }

    public function route(string $name, array $params = []): string
    {
        return $this->generate(Router::getNamedRoute($name)->getPath($params));
    }

    public function generate(string $path = '', array $params = []): string
    {
        $url = $this->baseURL . '/' . ltrim($path, '/');

        if (!empty($params))
        {
            $queryString = http_build_query($params);
            $url .= '?' . $queryString;
        }

        return $url;
    }

    public function encode(string $string): string
    {
        return urlencode($string);
    }

    public function decode(string $string): string
    {
        return urldecode($string);
    }

    public function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

}