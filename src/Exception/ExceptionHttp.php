<?php

namespace Foundation\Exception;

use Foundation\Exception\Http\ExceptionFieldFactory;
use Foundation\Kernels\Http\Kernel;
use Foundation\Kernels\Http\View;
use Illuminate\Contracts\Container\BindingResolutionException;
use HttpSoft\Emitter\SapiEmitter;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class ExceptionHttp implements ExceptionOutput
{
    public function __construct(
        private Kernel $kernel, private View $view, private string $pathTemplate)
    {

    }

    /**
     * @throws SyntaxError
     * @throws BindingResolutionException
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function show(\Throwable $exception): void
    {
        $exceptionFields = (new ExceptionFieldFactory($this->kernel))->create($exception)->getFields();

        $response = $this->view->response($this->pathTemplate, $exceptionFields->toArray());
        $response = $response->withStatus($exceptionFields->code);

        (new SapiEmitter())->emit($response);
    }
}