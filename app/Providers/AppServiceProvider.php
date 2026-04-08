<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // If running in Vercel Serverless environment (based on VERCEL environment variable)
        if (isset($_ENV['VERCEL']) || env('VERCEL', false)) {
            $tmpPath = '/tmp/storage/framework/views';
            
            if (!is_dir($tmpPath)) {
                mkdir($tmpPath, 0755, true);
            }
            
            config(['view.compiled' => $tmpPath]);
        }
    }
}
