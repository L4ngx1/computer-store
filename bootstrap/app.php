<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\AdminMiddleware::class,
            'api_token' => \App\Http\Middleware\ApiTokenMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            return $request->is('admin') || $request->is('admin/*')
                ? route('admin.login')
                : route('login.form');
        });

        $middleware->redirectUsersTo(function ($request) {
            if ($request->user()?->role === 'admin') {
                return route('admin.dashboard');
            }

            return route('home');
        });

        $middleware->validateCsrfTokens(except: [
            'api/v1/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
