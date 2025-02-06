<?php

namespace Foundation\Configuration;

use Foundation\Application;
use Foundation\Configuration\Load\LoadConfig;
use Foundation\Configuration\Load\LoadEnvironmentVariables;
use Foundation\Database\QueryBuilder;
use Foundation\Exception\ExceptionHandler;
use Foundation\Exception\ExceptionLogger;
use Foundation\Kernels\Kernel;
use Foundation\Kernels\Console\Kernel as ConsoleKernel;
use Foundation\Kernels\Http\Kernel as HttpKernel;
use Foundation\Log\Logger;
use Foundation\Mail\Mailer;
use Foundation\Url\UrlGenerator;

class ApplicationBuilder
{
    /**
     * Create a new application builder instance.
     */
    public function __construct(private Application $app)
    {
    }

    public function createKernel(): Kernel
    {
        $kernel = $this->app->runningInConsole() ?
            ConsoleKernel::class : HttpKernel::class;

        return $this->app->make($kernel, ['app' => $this->app]);
    }

    public function loadSettings(): static
    {
        $this->app->make(LoadEnvironmentVariables::class)->load($this->app);
        $this->app->make(LoadConfig::class)->load($this->app);

        return $this;
    }

    public function createLogger(): static
    {
        $this->app->instance('log', call_user_func(new Logger()));

        return $this;
    }

    public function registerExceptionHandler(): static
    {
        $this->app->instance(ExceptionHandler::class, call_user_func(new ExceptionHandler(
            new ExceptionLogger(app('log'))
        )));

        return $this;
    }

    public function createMailer(): static
    {
        $this->app->instance('mailer', call_user_func(new Mailer()));

        return $this;
    }

    public function createQueryBuilder(): static
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder()->setAsGlobal();

        return $this;
    }

    public function createUrlGenerator(): static
    {
        $this->app->instance('url', new UrlGenerator(config('app.url')));

        return $this;
    }

}
