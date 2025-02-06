<?php

namespace Foundation\Kernels\Http;

use Illuminate\Contracts\Container\BindingResolutionException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\LoaderInterface;

class View extends Environment
{
    public function __construct(LoaderInterface $loader, array $options = [])
    {
        parent::__construct($loader, $options);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws BindingResolutionException
     * @throws LoaderError
     */
    public function response(string $template, array $context = []): Response
    {
        return response($this->render($template, $context));
    }
}