<?php

namespace Pixan\PushNotifications\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pixan\PushNotifications\Transformers\PushNotificationTransformer;
use Pixan\PushNotifications\Models\PushNotification;
use Pixan\Api\Controllers\ApiController;

trait UserPushNotificationServices
{
    public function index($user_id, APIController $apiController, PushNotificationTransformer $pushNotificationTransformer)
    {
        $userModel = config('pixanpushnotifications.user_model', '\App\User');
        $user = $userModel::findOrFail($user_id);

        $pushNotifications = PushNotification::leftJoin('push_notification_user', function($join) use ($user_id) {
            $join->on('push_notifications.id', '=', 'push_notification_user.push_notification_id');
            $join->on('push_notification_user.user_id', '=', \DB::raw($user_id));
        })->select(\DB::raw('push_notifications.*, CASE WHEN push_notification_user.id IS NULL THEN 0 ELSE 1 END AS enabled'))->get();

        return $apiController->respondWithData([
            'pushNotifications' =>  $pushNotificationTransformer->transformCollection($pushNotifications->all())
        ]);
    }

    public function update($user_id, $pushNotification_id, Request $request, APIController $apiController, PushNotificationTransformer $pushNotificationTransformer)
    {
        $userModel = config('pixanpushnotifications.user_model', '\App\User');
        $user = $userModel::findOrFail($user_id);

        $validator = Validator::make($request->all(), [
            'status'	=> 'required|boolean'
        ]);

        if ($validator->fails()) {
            return $apiController->respondWithValidationErrors(
                $validator->errors()->all()
            );
        }

        $pushNotification = PushNotification::findOrFail($pushNotification_id);
        if (boolval($request->get('status'))) {
            if(!$pushNotification->users->contains($user_id))
            $pushNotification->users()->attach($user_id);
            $pushNotification->enabled = true;
        } else {
            $pushNotification->users()->detach($user_id);
            $pushNotification->enabled = false;
        }

        return $apiController->respondWithData([
            'pushNotification' => $pushNotificationTransformer->transform($pushNotification)
        ]);
    }
}
