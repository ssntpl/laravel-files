<?php

namespace Ssntpl\LaravelFiles;

use Illuminate\Support\ServiceProvider;
use Ssntpl\LaravelFiles\Cloud\CloudAdapter;

class LaravelFilesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/files.php', 'files');
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('cloud', function ($app, $config) {
            return new CloudAdapter($config['cache_time'], $config['cache_disk'], $config['remote_disks']);
        });

        $this->publishes([
            __DIR__ . "/../config/files.php" => config_path("files.php"),
        ], 'laravel-files-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'laravel-files-migrations');


        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
