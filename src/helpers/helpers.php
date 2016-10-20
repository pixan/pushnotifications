<?php
if ( ! function_exists('sendiOSPushNotification')){
    function sendiOSPushNotification($devices, $payload){
		if(!empty($devices)){

			$passphrase = config('pixanpushnotifications.options')[config('pixanpushnotifications.options.environment')]['password'];
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', config('pixanpushnotifications.options')[config('pixanpushnotifications.options.environment')]['certificate']);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);


			$fp = stream_socket_client(
				config('pixanpushnotifications.options')[config('pixanpushnotifications.options.environment')]['url'], $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if (!$fp)
			  exit("Failed to connect: $err $errstr" . PHP_EOL);

			$body['aps'] = array_merge([
				'sound' => 'default'
			], $payload);

			$payload = json_encode($body);


			// Build the binary notification
			$msg = "";
			foreach($devices as $device):
				try{
					$msg = chr(0) . pack('n', 32) . pack('H*', $device) . pack('n', strlen($payload)) . $payload;
					$result = fwrite($fp, $msg, strlen($msg));
				}catch(\Exception $e){

				}
			endforeach;
			fclose($fp);

		}
		return 0;
	}
}
?>
