<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        // ❌ Tidak pakai auth middleware
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        // ⛔ HENTIKAN redirect ke route('login')
        $exceptions->render(function (AuthenticationException $e) {
            abort(403, 'Authentication is disabled');
        });

    })

    ->create();
