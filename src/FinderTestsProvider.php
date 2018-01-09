<?php

namespace WDDA\LaravelFinderTests;

use Illuminate\Support\ServiceProvider;

class FinderTestsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('finder-tests.php'),
        ], 'finder-tests');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            FinderTestsCommand::class
        ]);
    }
}
