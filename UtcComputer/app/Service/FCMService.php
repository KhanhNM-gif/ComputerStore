<?php

namespace App\Service;

use GuzzleHttp\Client;

class FCMService
{

    private $apiConfig;

    public function __construct()
    {
        $this->apiConfig = [
            'url' => config('firebase.push_url'),
            'server_key' => config('firebase.server_key'),
            'device_type' => config('firebase.device_type')
        ];
    }

    /**
     * Sending push message to single user by Firebase
     *
     * @param string $device_token
     * @param array $notification
     * @param array $data
     *
     * @return bool|string
     */

    public function send(string $device_token, array $notification, array $data)
    {
        if ($data['device_type'] === $this->apiConfig['device_type']['ios']) {
            $fields = [
                'to'   => $device_token,
                'notification' => $notification,
                'data' => $data
            ];
        } else {
            $fields = [
                'to'   => $device_token,
                'data' => array_merge($data, $notification)
            ];
        }

        return $this->sendPushNotification($fields);
    }

    /**
     * Sending push message to multiple users by firebase
     * @param array $device_tokens
     * @param array $notification
     * @param array $data
     *
     * @return bool|string
     */
    public function sendMultiple(array $device_tokens, array $notification, array $data)
    {
        $fields = [
            'registration_ids' => $device_tokens,
            'data' => $data,
            'notification' => $notification
        ];

        return $this->sendPushNotification($fields);
    }

    /**
     * GuzzleHTTP request to firebase servers
     * @param array $fields
     *
     * @return bool
     */
    private function sendPushNotification(array $fields)
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'key=AAAAqIV8Ouk:APA91bEIRbxvSlq_vEc9v49eysPVOnnudGLamCe9nths9y0UYm3UU_UXPZpn-UddtJrjmz5Gcwfv6mPTw_c38p042ykRtR227UsdJJIPnLIjchn_6AM04kR76nbEhN-_WUqwdB3J_7gP',
            ]
        ]);
        $res = $client->post(
            $this->apiConfig['url'],
            ['body' => json_encode($fields)]
        );
        $res = json_decode($res->getBody());

        return true;
    }
}