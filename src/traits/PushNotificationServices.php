<?php

namespace Pixan\PushNotifications\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pixan\PushNotifications\Transformers\PushNotificationTransformer;
use Pixan\PushNotifications\Models\PushNotification;
use Pixan\Api\Controllers\ApiController;

trait PushNotificationServices
{
    public function index(APIController $apiController, PushNotificationTransformer $pushNotificationTransformer){
        $pushNotifications = PushNotification::all();

        return $apiController->respondWithData([
            'pushNotifications' =>  $pushNotificationTransformer->transformCollection($pushNotifications->all())
        ]);
    }

    public function show(APIController $apiController, PushNotificationTransformer $pushNotificationTransformer, $id){
        $pushNotification = PushNotification::find($id);

        if(!$pushNotification){
            return $apiController->respondNotFound();
        }

        return $apiController->respondWithData([
            'pushNotification' => $pushNotificationTransformer->transform($pushNotification)
        ]);
    }

    public function store(APIController $apiController, PushNotificationTransformer $pushNotificationTransformer, Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'active' => 'boolean'
        ]);
        if($validator->fails()){
            return $apiController->respondWithValidationErrors(
                $validator->errors()->all()
            );
        }

        $pushNotification = PushNotification::create($request->all());
        return $apiController->respondCreated([
            'pushNotification' => $pushNotification
        ]);

    }

    public function update(APIController $apiController, PushNotificationTransformer $pushNotificationTransformer, $id, Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'active' => 'boolean'
        ]);
        if($validator->fails()){
            return $apiController->respondWithValidationErrors(
                $validator->errors()->all()
            );
        }
        $pushNotification = PushNotification::find($id);

        if(!$pushNotification){
            return $apiController->respondNotFound();
        }

        $pushNotification->update($request->all());

        return $apiController->respondWithData(['pushNotification' => $pushNotification]);
    }

    public function destroy(APIController $apiController, PushNotificationTransformer $pushNotificationTransformer, $id){
        $pushNotification = PushNotification::find($id);

        if(!$pushNotification){
            return $apiController->respondNotFound();
        }

        $pushNotification->delete();

        return $apiController->respondWithData([
            'pushNotification' => $pushNotification
        ]);
    }
}