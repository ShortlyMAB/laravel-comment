<?php
declare(strict_types=1);

namespace Actuallymab\LaravelComment;

use Illuminate\Support\ServiceProvider;

class LaravelCommentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $configFile = __DIR__ . '/../config/comment.php';

        $this->publishes([
            $configFile => config_path('comment.php'),
        ], 'config');

        $this->mergeConfigFrom($configFile, 'comment');

        if (!class_exists('CreateCommentsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/../database/migrations/create_comments_table.php.stub' =>
                    database_path("migrations/{$timestamp}_create_comments_table.php")
            ], 'migrations');
        }
    }
}
