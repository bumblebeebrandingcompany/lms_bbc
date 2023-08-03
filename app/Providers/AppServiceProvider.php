<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Client;

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
