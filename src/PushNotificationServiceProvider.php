<?php

namespace Pixan\PushNotifications;

use Illuminate\Support\ServiceProvider;

class PushNotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //



		\Illuminate\Support\Facades\Route::model('pushnotification', Pixan\PushNotifications\Models\PushNotification::class);
        $this->publishes([
			__DIR__.'/config/pixanpushnotifications.php' => config_path('pixanpushnotifications.php'),
            __DIR__.'/migrations/2016_10_02_225028_create_push_notifications_table.php' => 'database/migrations/2016_10_02_225028_create_push_notifications_table.php',
            __DIR__.'/migrations/2016_10_11_225115_create_push_notification_user_table.php' => 'database/migrations/2016_10_11_225115_create_push_notification_user_table.php'
        ]);
	}

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
		$this->mergeConfigFrom(
            __DIR__.'/config/pixanpushnotifications.php', 'pixanpushnotifications'
        );
    }
}
