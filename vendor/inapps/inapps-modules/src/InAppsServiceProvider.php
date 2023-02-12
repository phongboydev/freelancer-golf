<?php

namespace InApps\IAModules;

use Composer\Autoload\ClassLoader;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use InApps\IAModules\Console\CreateModule;
use InApps\IAModules\Helpers\BaseHeader;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class InAppsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Bind BaseHeader singleton class
        $this->app->singleton(BaseHeader::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register default middleware
        $this->middlewareGroup('InApps', [BaseMiddleware::class]);

        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    CreateModule::class,
                ]
            );
        }
        if (is_dir(base_path() . '/modules')) {
            $loader = new ClassLoader();
            $admin_dir = new RecursiveDirectoryIterator(base_path() . '/modules', false);
            $admin_iterator = new \RecursiveIteratorIterator($admin_dir);
            $providers = [];
            foreach ($admin_iterator as $file) {
                $filename = $file->getFilename();
                if (preg_match('%module_info.json$%', $filename)) {
                    $settings = $this->get_file_data($file->getPathname());
                    //Push provider
                    $providers[] = $settings->provider;
                    //Loader map namespace to src folder
                    $loader->setPsr4($settings->moduleNamespace, $settings->source_path);
                }
            }

            //register loader
            $loader->register();
            foreach ($providers as $provider) {
                if (class_exists($provider)) {
                    $this->app->register($provider);
                }
            }
        }
    }

    public function get_file_data($path_name)
    {
        $file_content = file_get_contents($path_name);
        $settings = json_decode($file_content);
        $settings->source_path = str_replace("module_info.json", 'src', $path_name);
        return $settings;
    }
}
