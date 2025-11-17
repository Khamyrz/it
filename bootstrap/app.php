<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\LogTraffic::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle database connection errors globally
        $exceptions->render(function (\PDOException $e, \Illuminate\Http\Request $request) {
            if ($request->is('login') || $request->is('login/*')) {
                \Illuminate\Support\Facades\Log::error('Database PDO error on login: ' . $e->getMessage());
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Database connection error. Please verify your database credentials.'], 500);
                }
                return redirect()->back()->withErrors(['email' => 'Database connection error. Please verify your database credentials in the hosting control panel.'])->withInput();
            }
        });
        
        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            if ($request->is('login') || $request->is('login/*')) {
                \Illuminate\Support\Facades\Log::error('Database Query error on login: ' . $e->getMessage());
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Database connection error. Please check your database configuration.'], 500);
                }
                return redirect()->back()->withErrors(['email' => 'Database connection error. Please check your database configuration.'])->withInput();
            }
        });
    })->create();
