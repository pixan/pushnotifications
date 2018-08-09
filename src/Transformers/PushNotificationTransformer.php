<?php
namespace Pixan\PushNotifications\Transformers;

use Pixan\Api\Transformers\Transformer;

class PushNotificationTransformer extends Transformer
{
    public function transform($pushNotification)
    {
        $transformation = [
            "id"      => $pushNotification["id"],
            "title"   => $pushNotification["title"],
            "active"  => $pushNotification["active"],
            "enabled" => isset($pushNotification["enabled"]) ? (bool) $pushNotification["enabled"] : false
        ];
        return $transformation;
    }
}
