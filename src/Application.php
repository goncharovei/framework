<?php

namespace Foundation;

use Composer\Autoload\ClassLoader;
use Foundation\Configuration\ApplicationBuilder;
use Foundation\Configuration\Env;
use Illuminate\Container\Container;

class Application extends Container
{

    /**
     * The Package version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The base path for the Package installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The custom bootstrap path defined by the developer.
     *
     * @var string
     */
    protected $bootstrapPath;

    /**
     * The custom application path defined by the developer.
     *
     * @var string
     */
    protected $appPath;

    /**
     * The custom configuration path defined by the developer.
     *
     * @var string
     */
    protected $configPath;

    /**
     * The custom public / web path defined by the developer.
     *
     * @var string
     */
    protected $publicPath;

    /**
     * The custom storage path defined by the developer.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The custom environment path defined by the developer.
     *
     * @var string
     */
    protected $environmentPath;

    /**
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     * Indicates if the application is running in the console.
     *
     * @var bool|null
     */
    protected $isRunningInConsole;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a new Illuminate application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();

    }

    protected function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
    }

    /**
     *  Begin configuring a new Package application instance.
     *
     * @param string|null $basePath
     * @return ApplicationBuilder
     */
    public static function configure(?string $basePath = null): ApplicationBuilder
    {
        $basePath = match (true) {
            is_string($basePath) => $basePath,
            default => static::inferBasePath(),
        };

        return (new ApplicationBuilder(new static($basePath)))
            ->loadSettings()
            ->createLogger()
            ->registerExceptionHandler()
            ->createUrlGenerator()
            ->createMailer()
            ->createQueryBuilder();
    }

    /**
     * Infer the application's base directory from the environment.
     *
     * @return string
     */
    public static function inferBasePath(): string
    {
        return match (true) {
            isset($_ENV['APP_BASE_PATH']) => $_ENV['APP_BASE_PATH'],
            default => dirname(array_keys(ClassLoader::getRegisteredLoaders())[0]),
        };
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath): static
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer(): void
    {
        $this->instance('path', $this->path());
        $this->instance('path.base', $this->basePath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.public', $this->publicPath());
        $this->instance('path.resources', $this->resourcePath());
        $this->instance('path.storage', $this->storagePath());

        $this->useBootstrapPath($this->basePath('bootstrap'));

    }

    public function useBootstrapPath($path): static
    {
        $this->bootstrapPath = $path;

        $this->instance('path.bootstrap', $path);

        return $this;
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @param  string  $path
     * @return string
     */
    public function path($path = ''): string
    {
        return $this->joinPaths($this->appPath ?: $this->basePath('app'), $path);
    }

    /**
     * Get the base path of the Package installation.
     *
     * @param  string  $path
     * @return string
     */
    public function basePath($path = ''): string
    {
        return $this->joinPaths($this->basePath, $path);
    }

    /**
     * Get the path to the bootstrap directory.
     *
     * @param  string  $path
     * @return string
     */
    public function bootstrapPath($path = ''): string
    {
        return $this->joinPaths($this->bootstrapPath, $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path
     * @return string
     */
    public function configPath($path = ''): string
    {
        return $this->joinPaths($this->configPath ?: $this->basePath('config'), $path);
    }

    /**
     * Get the path to the public / web directory.
     *
     * @param  string  $path
     * @return string
     */
    public function publicPath($path = ''): string
    {
        return $this->joinPaths($this->publicPath ?: $this->basePath('public'), $path);
    }

    /**
     * Get the path to the storage directory.
     *
     * @param  string  $path
     * @return string
     */
    public function storagePath($path = ''): string
    {
        return $this->joinPaths($this->storagePath ?: $this->basePath('storage'), $path);
    }

    /**
     * Get the path to the resources directory.
     *
     * @param  string  $path
     * @return string
     */
    public function resourcePath($path = ''): string
    {
        return $this->joinPaths($this->basePath('resources'), $path);
    }

    /**
     * Join the given paths together.
     *
     * @param  string  $basePath
     * @param  string  $path
     * @return string
     */
    public function joinPaths($basePath, $path = ''): string
    {
        return join_paths($basePath, $path);
    }

    /**
     * Get the path to the environment file directory.
     *
     * @return string
     */
    public function environmentPath(): string
    {
        return $this->environmentPath ?: $this->basePath;
    }

    /**
     * Get the environment file the application is using.
     *
     * @return string
     */
    public function environmentFile(): string
    {
        return $this->environmentFile ?: '.env';
    }

    /**
     * Get the fully qualified path to the environment file.
     *
     * @return string
     */
    public function environmentFilePath(): string
    {
        return $this->environmentPath().DIRECTORY_SEPARATOR.$this->environmentFile();
    }

    /**
     * Determine if the application is in the local environment.
     *
     * @return bool
     */
    public function isLocal(): bool
    {
        return $this['env'] === 'local';
    }

    /**
     * Determine if the application is in the production environment.
     *
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this['env'] === 'production';
    }

    /**
     * Detect the application's current environment.
     *
     * @param  \Closure  $callback
     * @return string
     */
    public function detectEnvironment(\Closure $callback): string
    {
        return $this['env'] = $callback();
    }

    /**
     * Determine if the application is running in the console.
     *
     * @return bool
     */
    public function runningInConsole(): ?bool
    {
        if ($this->isRunningInConsole === null) {
            $this->isRunningInConsole = Env::get('APP_RUNNING_IN_CONSOLE') ?? (\PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg');
        }

        return $this->isRunningInConsole;
    }

    /**
     * Determine if the application is running with debug mode enabled.
     *
     * @return bool
     */
    public function hasDebugModeEnabled(): bool
    {
        return (bool) $this['config']->get('app.debug');
    }

    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getNamespace(): string
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents($this->basePath('composer.json')), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath($this->path()) === realpath($this->basePath($pathChoice))) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new \RuntimeException('Unable to detect application namespace.');
    }
}
