<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;

class ProblemDetailExceptionsProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/config/problem-detail-exceptions.php' => config_path('shoppingcart.php'),
            ],
            'config'
        );
    }
}
