<?php

namespace Pixan\PushNotifications\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['user_id', 'ios_device_token', 'android_device_token', 'active'];

    public function users()
    {
        return $this->belongsToMany(config('pixanpushnotifications.user_model'), 'user_id');
    }
}
