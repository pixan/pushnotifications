<?php

namespace Pixan\PushNotifications\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = ['title', 'active'];

    public function users()
    {
        return $this->belongsToMany(
            config('pixanpushnotifications.user_model', '\App\User'),
            'push_notification_user', 'user_id', 'push_notification_id'
        );
    }
}
