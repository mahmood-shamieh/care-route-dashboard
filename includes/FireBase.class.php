<?php
class FireBase
{
    var $fcmKey = '';
    function __construct($fcmKey)
    {
        $this->fcmKey = $fcmKey;
    }
    function sendNotification($token, $title = '', $body = '', $payload = '')
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "to": "' . $token . '",
                                        "notification": {
                                            "title": "' . $title . '",
                                            "body": "' . $body . '",
                                            "payload":"' . time() . '"
                                        }
                                    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: key=' . $this->fcmKey . ''
            ),
        ));

        $response = curl_exec($curl);
        // print($body);

        // print($response);
        // die;

        curl_close($curl);
        // echo $response;
    }
}
