<?php

namespace Pixan\PushNotifications;

use Illuminate\Support\ServiceProvider;

class BasicServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
		\Illuminate\Support\Facades\Route::model('pushnotification', Pixan\Basic\Models\PushNotification::class);
        $this->publishes([__DIR__.'/migrations/2016_10_02_225028_create_push_notifications_table.php' => 'database/migrations/2016_10_02_225028_create_push_notifications_table.php']);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
