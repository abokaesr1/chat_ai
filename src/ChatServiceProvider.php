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

        // Load the Assets
        $this->publishes([
            __DIR__ . '/../src/migrations' => database_path('migrations'),
            __DIR__ . '/../src/views' => resource_path('views/chat_ai'),
        ], 'ChatAI');
        // add the provider dynmic


        //$this->mergeConfigFrom(__DIR__ . '/../config/chat_ai.php', 'chat_ai');
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}
}
