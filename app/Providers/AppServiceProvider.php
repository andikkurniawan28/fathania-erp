<?php

namespace App\Providers;

use App\Models\Setup;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // if(config('app.env') === "local"){
        //     URL::forceScheme('https');
        // }
        View::composer('*', function ($view) {
            $view->with('formatBySetup', function($value) {
                // Ambil setup pertama dari database
                $setup = Setup::first();
                $decimalSeparator = $setup->decimal_separator;
                $thousandSeparator = $setup->thousand_separator;

                // Format angka menggunakan separator yang sesuai
                return number_format($value, 2, $decimalSeparator, $thousandSeparator);
            });
        });
    }
}
