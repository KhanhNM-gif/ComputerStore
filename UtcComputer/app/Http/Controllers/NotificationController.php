<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\PushNotificationTraist;


class NotificationController extends Controller
{
    use PushNotificationTraist;
    public function sendPush(Request $request)
    {
        $fields = $request->validate([
            'DeviceToken' => 'required|string'
        ]);

        $totalUnread = 1;
        $data = [
            'func_name' => config('firebase.notification.func'),
            'screen' => config('firebase.notification.screen'),
            'total_unread' => $totalUnread,
            'total_count' => 2,
            'device_type' => 'web', // Loại device, có thể là androi, web, ios
        ];

        $content = [
            "title" => "hello", // tiêu đề tin nhắn
            "body" => "test", // nội dung tin nhắn
            'badge' => $totalUnread, // số message chưa đọc
            'sound' => config('firebase.sound') // âm báo tin nhắn
        ];

        //Push notification
        $this->pushMessage($fields['DeviceToken'], $content, $data);
    }
}