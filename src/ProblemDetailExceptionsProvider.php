<?php

declare(strict_types=1);

namespace Gsousadev\LaravelProblemDetailExceptions;

use Illuminate\Support\ServiceProvider;


class ProblemDetailExceptionsProvider extends ServiceProvider
{
    public function register()
    {
        if (! app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/problem-detail-exceptions.php', 'problem-detail-exceptions');
        }
    }

    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/config/problem-detail-exceptions.php' => config_path('problem-detail-exceptions.php'),
            ],
            'config'
        );

        $this->publishes(
            [
                __DIR__ . '/Exceptions/ExampleException.php' => app_path('Exceptions/ExampleException.php')
            ]
        );
    }
}
