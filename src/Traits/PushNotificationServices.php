<?php

namespace Pixan\PushNotifications\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pixan\PushNotifications\Transformers\PushNotificationTransformer;
use Pixan\PushNotifications\Models\PushNotification;
use Pixan\Api\Controllers\ApiController;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use Pixan\PushNotifications\Models\Device;

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

	public function test(APIController $apiController){

		if(\Auth::check()){
			$devices = Device::where('user_id', \Auth::user()->id)->where('active', true)->orderBy('created_at', 'desc')->pluck('ios_device_token')->all();
			sendiOSPushNotification($devices, [
				'type'	=> config('pixanpushnotifications.options.NOTIFICATION_TYPE_VERIFY'),
				'alert' => '¡Todo en orden! Las notificaciones para su aplicación están funcionando correctamente'
			]);

			return $apiController->setMessages(['Se envió una notificación de prueba a su dispositivo'])->respondWithData([]);
		}

		return $apiController->respondWithErrors([
			'Este método solo está disponible para usuarios firmados'
		], SymfonyResponse::HTTP_UNAUTHORIZED);
	}
}
