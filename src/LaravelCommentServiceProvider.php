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
        $timestamp = date('Y_m_d_His', time());

        $this->publishes([
            __DIR__ . '/../database/migrations/create_comments_table.php.stub' => $this->app->databasePath()
                . "/migrations/{$timestamp}_create_comments_table.php",
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
