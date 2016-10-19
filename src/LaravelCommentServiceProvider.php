<?php

namespace Actuallymab\LaravelComment;

use Illuminate\Support\ServiceProvider;

class LaravelCommentServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
    }
}
