<?php

namespace FilippoToso\ControllersGenerator;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

use FilippoToso\ModelsGenerator\GenerateModels;

class ServiceProvider extends EventServiceProvider
{

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

        parent::boot();

        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'controllers-generator');

        $this->publishes([
            dirname(__DIR__) . '/resources/views' => resource_path('views/vendor/controllers-generator'),
        ], 'views');

        $this->publishes([
            dirname(__DIR__) . '/config/controllers-generator.php' => config_path('controllers-generator.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateController::class
            ]);
        }

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/default.php',
            'controllers-generator'
        );

    }

}
