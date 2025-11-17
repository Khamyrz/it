<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Explicitly ensure User model is loaded and registered for auth
        // This helps when autoloader cache is stale on the server
        if (file_exists(app_path('Models/User.php'))) {
            require_once app_path('Models/User.php');
        }
        
        // Explicitly register User model for auth system
        $this->app->bind('auth.providers.users.model', function () {
            return \App\Models\User::class;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Schedule automatic database export every hour
        Schedule::command('database:auto-export')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();
    }
}
