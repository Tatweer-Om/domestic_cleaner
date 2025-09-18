<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\EnsureGuestToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add SetLocale only to "web" group
        $middleware->appendToGroup('web', SetLocale::class);

        // Add EnsureGuestToken globally
        $middleware->append(EnsureGuestToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
