<?php

namespace Pixan\PushNotifications\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    //
    protected $fillable = ['title', 'active'];

    public function users(){
        return $this->belongsToMany('App\User');
    }

}
