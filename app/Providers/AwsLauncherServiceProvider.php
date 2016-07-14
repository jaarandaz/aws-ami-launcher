<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\AwsLauncherService;

class AwsLauncherServiceProvider extends ServiceProvider 
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('awslauncher', function () {
            return new AwsLauncherService();
        });
    }

}
