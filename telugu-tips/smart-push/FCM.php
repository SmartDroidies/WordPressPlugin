<?php

class FCM {
 
    //put your code here
    // constructor
    function __construct() {
    }
 
    /**
     * Sending Push Notification
     */
    public function send_notification($data, $notification) {
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to' => "/topics/global",
            'data' => $data
            /* 'notification' => $notification */
        );
 
        $headers = array(
            'Authorization: key=AIzaSyDzqTYPO-CW3PhwDW0Ywg-ZnRhcues6suM', 
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
        //echo $result;
        //die();
    }
 
}
?>