<?php

namespace App\Libraries;

use DB;
use Config;

class PushNotificationLibrary
{
    /**
     * Send Notification To Android Using Firebase.
     *
     * @param  array  $data
     * @return mixed
     */

    public static function sendFCMNotification($datas)
    {

        //Device-ID
        $firebaseToken = $datas['include_player_ids'];

        // $SERVER_API_KEY = 'AAAA7np-KG4:APA91bF_TPd5S8OmUORHdyuYPei-t_N4M9uCZtgNPy4s-hmHrHELqr3p4hdFCDhd_KiNrE9mnUs0NYfJhzctKe8kY_jsn4oCu0GJzyp2ZxM-6RV3L-vZObJkx-IGAk0ibv4Bscpv7GJx';
        $SERVER_API_KEY = 'AAAAWLRdl-c:APA91bExMBuh-Bin9L2AZbfT7DqpyoPQWidaUTYWs0UpGRVBwd3d0BIm0miBP7KH1Png_9v9goJRCW84a4ZJ-I0rLyoAyWq58JY-b5sB5bDEXPkjP7ol17cqwQklQPU4JdVITreI_IMn';

        $firebaseTokenArray =  array($firebaseToken);

        $datas['additional_data']['body']=$datas['message'];
        $datas['additional_data']['title']=$datas['notifications_title'];

        /*$data = [
            "registration_ids" => $firebaseTokenArray,
            "data" => [
                "title" => $datas['notifications_title'],
                "body" => $datas['message'],
            ]
        ];*/

        $data = [
            "registration_ids" => $firebaseTokenArray,
            "data" => $datas['additional_data']
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        // Disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        //print_r($response);
        curl_close($ch);
    }

    /**
     * Send Notification To IOS Using Firebase.
     *
     * @param  array  $data
     * @return mixed
     */
    public static function sendFCMNotificationIOS($datas)
    {
        //Device-ID
        $firebaseToken = $datas['include_player_ids'];

        //$SERVER_API_KEY = 'AAAA7np-KG4:APA91bF_TPd5S8OmUORHdyuYPei-t_N4M9uCZtgNPy4s-hmHrHELqr3p4hdFCDhd_KiNrE9mnUs0NYfJhzctKe8kY_jsn4oCu0GJzyp2ZxM-6RV3L-vZObJkx-IGAk0ibv4Bscpv7GJx';
        $SERVER_API_KEY = 'AAAAWLRdl-c:APA91bExMBuh-Bin9L2AZbfT7DqpyoPQWidaUTYWs0UpGRVBwd3d0BIm0miBP7KH1Png_9v9goJRCW84a4ZJ-I0rLyoAyWq58JY-b5sB5bDEXPkjP7ol17cqwQklQPU4JdVITreI_IMn';

        $firebaseTokenArray =  array($firebaseToken);

        $datas['additional_data']['body'] = $datas['message'];
        $datas['additional_data']['title'] = $datas['notifications_title'];

        $aps['aps'] = [
            'alert' => [
                'title' => $datas['notifications_title'],
                'body' => $datas['message'],
            ],
            'badge' => 0,
            'sound' => 'default',
            'title' => $datas['notifications_title'],
            'body' => $datas['message'],
            'my_value_1' =>   $datas['additional_data'],
        ];
        $result = [
            "registration_ids" => $firebaseTokenArray,
            "notification" => $aps['aps'],
        ];

        //Generating JSON encoded string form the above array.
        $json = json_encode($result);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        // Disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        // print_r($response);
        curl_close($ch);
    }
}
