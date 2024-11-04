<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use app\Http\Middleware\CheckToken;
use Psy\VersionUpdater\Checker;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check' => \App\Http\Middleware\CheckToken::class,
            'check_customer' => \App\Http\Middleware\registerCustomer::class,
            'check_get_customer' => \App\Http\Middleware\checkGetCustomer::class,
            'register_log' => \App\Http\Middleware\LogRequests::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();