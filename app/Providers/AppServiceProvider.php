<?php

namespace App\Providers;

use App\Category;
use App\Observers\CategoryObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Carbon::setLocale('ru');
        Category::observe(CategoryObserver::class);
        Gate::define('update-listing', function($user,$listing)
        {
            return $user->id == $listing->user_id;
        });
        Gate::define('delete-listing', function($user,$listing)
        {
           return $user->id = $listing->user_id;
        });
        Gate::define('upload-photo', function($user,$listing)
        {
            return $user->id == $listing->user_id;
        });
        Gate::define('remove-photo', function($user,$listing)
        {
            return $user->id == $listing->user_id;
        });
        Gate::define('toggle-favorite', function($user,$listing)
        {
            return $user->id != $listing->user_id;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
