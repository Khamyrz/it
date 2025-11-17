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
        // Handle class not found errors (especially User model)
        $exceptions->render(function (\Error $e, \Illuminate\Http\Request $request) {
            $message = $e->getMessage();
            if (strpos($message, 'AppModelsUser') !== false || strpos($message, 'App\\Models\\User') !== false || strpos($message, 'Class') !== false && strpos($message, 'User') !== false) {
                \Illuminate\Support\Facades\Log::error('User model class not found: ' . $message, [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => substr($e->getTraceAsString(), 0, 500)
                ]);
                if ($request->is('login') || $request->is('login/*')) {
                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Configuration error. Please run: composer dump-autoload && php artisan config:clear on the server.'], 500);
                    }
                    return redirect()->back()->withErrors(['email' => 'System configuration error. Please contact administrator to run: composer dump-autoload && php artisan config:clear'])->withInput();
                }
            }
        });
        
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
        
        // Handle errors on manage-room route
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('manage-room') || $request->is('manage-room/*')) {
                \Illuminate\Support\Facades\Log::error('Error on manage-room: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => substr($e->getTraceAsString(), 0, 500)
                ]);
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'An error occurred. Please try again.'], 500);
                }
                return redirect()->route('dashboard')->withErrors(['error' => 'An error occurred loading the room management page. Please try again.']);
            }
        });
    })->create();
