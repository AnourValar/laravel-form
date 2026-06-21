<?php

namespace AnourValar\LaravelForm\Tests;

abstract class AbstractSuite extends \Orchestra\Testbench\TestCase
{
    /**
     * Init
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            \AnourValar\LaravelForm\Providers\LaravelFormServiceProvider::class,
            \AnourValar\LaravelAtom\Providers\LaravelAtomServiceProvider::class,
            \AnourValar\ConfigHelper\Providers\ConfigHelperServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'ConfigHelper' => \AnourValar\ConfigHelper\Facades\ConfigHelperFacade::class,
        ];
    }

    /**
     * Builds a ViewErrorBag from a list of "field => messages".
     *
     * @param array $messages
     * @return \Illuminate\Support\ViewErrorBag
     */
    protected function errorBag(array $messages = []): \Illuminate\Support\ViewErrorBag
    {
        $bag = new \Illuminate\Support\ViewErrorBag();
        $bag->put('default', new \Illuminate\Support\MessageBag($messages));

        return $bag;
    }

    /**
     * Builds a ViewErrorBag and shares it with the views (as the framework does on validation failure).
     *
     * @param array $messages
     * @return \Illuminate\Support\ViewErrorBag
     */
    protected function shareErrors(array $messages): \Illuminate\Support\ViewErrorBag
    {
        $bag = $this->errorBag($messages);
        \Illuminate\Support\Facades\View::share('errors', $bag);

        return $bag;
    }

    /**
     * Flashes "old input" into the session (as the framework does on validation failure).
     *
     * @param array $old
     * @return void
     */
    protected function setOld(array $old): void
    {
        $session = $this->app['session']->driver();
        $session->put('_old_input', $old);
        $this->app['request']->setLaravelSession($session);
    }
}
