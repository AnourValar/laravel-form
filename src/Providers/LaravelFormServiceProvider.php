<?php

namespace AnourValar\LaravelForm\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelFormServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // config
        $this->mergeConfigFrom(__DIR__.'/../resources/config/form.php', 'form');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // config
        $this->publishes([ __DIR__.'/../resources/config/form.php' => config_path('form.php')], 'config');

        // views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'form');

        $this->loadViewComponentsAs(config('form.namespace'), [
            \AnourValar\LaravelForm\Components\Input::class,
            \AnourValar\LaravelForm\Components\Select::class,
            \AnourValar\LaravelForm\Components\Textarea::class,
            \AnourValar\LaravelForm\Components\Hidden::class,
        ]);
    }
}
