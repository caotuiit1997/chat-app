<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ShowUserNameProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path(). '/Helpers/show_user_name.php';
    }
}
