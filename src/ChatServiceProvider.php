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
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Load the Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'Salamat/chat_ai');
        // Load the Assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/chat_ai'),
            __DIR__ . '/../database/migrations' => database_path('migrations'),
            __DIR__ . '/../resources/views' => resource_path('views/chat_ai'),


        ], 'ChatAI', true);
        // add the provider dynmic
        app()->register(ChatServiceProvider::class);


        //$this->mergeConfigFrom(__DIR__ . '/../config/chat_ai.php', 'chat_ai');
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}
}
