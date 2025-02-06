<?php

namespace Foundation\Kernels\Http;

use Foundation\Application;
use Foundation\Exception\ExceptionHandler;
use Foundation\Exception\ExceptionHttp;
use Foundation\Kernels\Http\View\Twig\TwigExtension;
use Illuminate\Contracts\Container\BindingResolutionException;
use HttpSoft\Emitter\SapiEmitter;
use League\Route\Router;
use Symfony\Component\Finder\Finder;
use Foundation\Kernels\Kernel as FoundationKernel;

final class Kernel extends FoundationKernel implements KernelHttp
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->registerTemplateEngine();
        $this->registerRequest();
        $this->registerRouter();
        $this->registerResponse();
    }

    /**
     * @throws BindingResolutionException
     */
    public function launchWeb(): void
    {
        $response = router()->dispatch(request());
        (new SapiEmitter())->emit($response);
    }

    public function loadRoutes(string $path): Kernel
    {
        foreach (Finder::create()->files()->name('*.php')->in($path) as $file)
        {
            require_once $file->getRealPath();
        }

        return $this;
    }

    public function addTwigExtension($extension): Kernel
    {
        view()->addExtension($extension);

        return $this;
    }

    public function registerExceptionOutput(string $pathTemplate): Kernel
    {
        /**
         * @var ExceptionHandler $handler
         */
        $handler = $this->app->make(ExceptionHandler::class);
        $handler->setOutput(new ExceptionHttp(
            $this,
            view(),
            $pathTemplate
        ));

        return $this;
    }

    private function registerTemplateEngine(): void
    {
        $this->app->instance('view', new View(
            new \Twig\Loader\FilesystemLoader($this->app->resourcePath('view'))
        ));

        view()->addExtension(new TwigExtension());
    }

    private function registerRouter(): void
    {
        $this->app->instance('router', new Router());
    }

    private function registerRequest(): void
    {
        $this->app->instance('request', Request::fromGlobals());
    }

    private function registerResponse(): void
    {
        $this->app->instance('response', new Response());
    }
}