<?php
namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;
use App\Category;
use App\User;

class ComposerServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using Closure based composers...
        View::composer('layouts.app', function($view)
        {
            $view->with('categories', Category::popular(4));
            $view->with('registrationOpen', User::registrationOpen());
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}