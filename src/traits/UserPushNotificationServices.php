<?php

namespace Pixan\PushNotifications\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pixan\PushNotifications\Transformers\PushNotificationTransformer;
use Pixan\PushNotifications\Models\PushNotification;
use Pixan\Api\Controllers\ApiController;
use App\User;

trait UserPushNotificationServices
{
    public function index($user_id, APIController $apiController, PushNotificationTransformer $pushNotificationTransformer){
        $user = User::findOrFail($user_id);

        $pushNotifications = PushNotification::leftJoin('push_notification_user', function($join) use ($user_id) {
            $join->on('push_notifications.id', '=', 'push_notification_user.push_notification_id');
            $join->on('push_notification_user.user_id', '=', \DB::raw($user_id));
        })->select(\DB::raw('push_notifications.*, CASE WHEN push_notification_user.id IS NULL THEN 0 ELSE 1 END AS enabled'))->get();

        return $apiController->respondWithData([
            'pushNotifications' =>  $pushNotificationTransformer->transformCollection($pushNotifications->all())
        ]);
    }

    public function update($user_id, Request $request, APIController $apiController, PushNotificationTransformer $pushNotificationTransformer){
        $user = User::findOrFail($user_id);

        $validator = Validator::make($request->all(), [
           'pushNotification_id' => 'required|array|exists:push_notifications,id'
        ]);

        if($validator->fails()){
            return $apiController->respondWithValidationErrors(
                $validator->errors()->all()
            );
        }

        $pushNotifications = PushNotification::all();
        foreach($pushNotifications as $pushNotification){
            $pushNotification->users()->detach();
        }

        $pushNotifications = PushNotification::find($request->get('pushNotification_id'));
        foreach($pushNotifications as $pushNotification){
            $pushNotification->users()->syncWithoutDetaching([$user_id]);
            $pushNotification->enabled = true;
        }

        return $apiController->respondWithData([
            'pushNotification' => $pushNotificationTransformer->transformCollection($pushNotifications->all())
        ]);

    }

}
