<?php

namespace Foundation\Configuration\Load;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Foundation\Application;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class LoadConfig
{
    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     * @throws \Exception
     */
    public function load(Application $app): void
    {
        $items = [];

        // Next we will spin through all the configuration files in the configuration
        // directory and load each one into the repository. This will make all the
        // options available to the developer for use in various parts of this app.
        $app->instance('config', $config = new Repository($items));

        $this->loadConfigurationFiles($app, $config);

        $app->detectEnvironment(fn () => $config->get('app.env', 'production'));

        date_default_timezone_set($config->get('app.timezone', 'UTC'));

        mb_internal_encoding('UTF-8');
    }

    /**
     * Load the configuration items from all the files.
     *
     * @param Application $app
     * @param RepositoryContract $repository
     * @return void
     *
     */
    protected function loadConfigurationFiles(Application $app, RepositoryContract $repository): void
    {
        $files = $this->getConfigurationFiles($app);

        $base = $this->getBaseConfiguration();

        foreach (array_diff(array_keys($base), array_keys($files)) as $name => $config) {
            $repository->set($name, $config);
        }

        foreach ($files as $name => $path) {
            $base = $this->loadConfigurationFile($repository, $name, $path, $base);
        }

        foreach ($base as $name => $config) {
            $repository->set($name, $config);
        }
    }

    /**
     * Load the given configuration file.
     *
     * @param RepositoryContract $repository
     * @param  string  $name
     * @param  string  $path
     * @param  array  $base
     * @return array
     */
    protected function loadConfigurationFile(RepositoryContract $repository, $name, $path, array $base): array
    {
        $config = (fn () => require $path)();

        if (isset($base[$name]))
        {
            $config = array_merge($base[$name], $config);
            unset($base[$name]);
        }

        $repository->set($name, $config);

        return $base;
    }

    /**
     * @param Application $app
     * @return array
     */
    protected function getConfigurationFiles(Application $app): array
    {
        $files = [];

        $configPath = realpath($app->configPath());

        if (! $configPath) {
            return [];
        }

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);

            $files[$directory.basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  \SplFileInfo  $file
     * @param  string  $configPath
     * @return string
     */
    protected function getNestedDirectory(SplFileInfo $file, $configPath)
    {
        $directory = $file->getPath();

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
        }

        return $nested;
    }

    /**
     * Get the base configuration files.
     *
     * @return array
     */
    protected function getBaseConfiguration()
    {
        $config = [];

        foreach (Finder::create()->files()->name('*.php')->in(__DIR__.'/../../../../config') as $file) {
            $config[basename($file->getRealPath(), '.php')] = require $file->getRealPath();
        }

        return $config;
    }
}
