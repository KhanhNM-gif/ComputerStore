<?php

return [
    'push_url' => 'https://fcm.googleapis.com/fcm/send',
    'server_key' => env('AAAAqIV8Ouk:APA91bEIRbxvSlq_vEc9v49eysPVOnnudGLamCe9nths9y0UYm3UU_UXPZpn-UddtJrjmz5Gcwfv6mPTw_c38p042ykRtR227UsdJJIPnLIjchn_6AM04kR76nbEhN-_WUqwdB3J_7gP', null), // Lấy trong tài khoản firebase
    'device_type' => [
        'ios' => 'iOS',
        'android' => 'android'
    ],
    'sound' => 'default'
];
