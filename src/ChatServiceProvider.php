<?php

namespace Salamat\chat_ai;

use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load the Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        // Load the Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../src/migrations');
        // Load the Views
        $this->loadViewsFrom(__DIR__ . '/../src/views', 'Salamat/chat_ai');
        // Load the Config
        $this->mergeConfigFrom(__DIR__ . '/../config/chat.php', 'chat');
        // Load the Assets
        $this->publishes([
            __DIR__ . '/../config/chat.php' => config_path('chat.php'),
            __DIR__ . '/../src/migrations' => database_path('migrations'),
            __DIR__ . '/../src/views' => resource_path('views/chat_ai'),
        ], 'ChatAI');
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}
}
