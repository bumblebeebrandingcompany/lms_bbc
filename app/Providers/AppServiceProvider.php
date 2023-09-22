<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Client;
use Illuminate\Support\Facades\Blade;
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
        Blade::directive('format_date', function ($date) {
            if (!empty($date)) {
                return "\Carbon\Carbon::createFromTimestamp(strtotime($date))->format('M d Y')";
            } else {
                return null;
            }
        });

        Blade::directive('format_time', function ($date) {
            if (!empty($date)) {
                return "\Carbon\Carbon::createFromTimestamp(strtotime($date))->format('h:i A')";
            } else {
                return null;
            }
        });

        Blade::directive('format_datetime', function ($date) {
            if (!empty($date)) {
                return "\Carbon\Carbon::createFromTimestamp(strtotime($date))->format('M d Y h:i A')";
            } else {
                return null;
            }
        });
        
        Blade::directive('get_diff_for_humans', function ($date) {
            if (!empty($date)) {
                return "\Carbon\Carbon::createFromTimestamp(strtotime($date))->diffForHumans()";
            } else {
                return null;
            }
        });

        /*
        * Share clients across pages
        */
        View::composer('*', function ($view) {

            $__global_clients_filter = request()->session()->get('__global_clients_filter', []);
            $__global_clients_drpdwn = Client::pluck('name', 'id')->toArray();
            
            $view->with(compact('__global_clients_filter', '__global_clients_drpdwn'));
        });
    }
}
