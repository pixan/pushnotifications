<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushNotificationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('push_notification_user', function(Blueprint $table){
			$table->increments('id');
            $table->integer('push_notification_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('push_notification_id')->references('id')->on('push_notifications')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		Schema::drop('push_notification_user');
    }
}
