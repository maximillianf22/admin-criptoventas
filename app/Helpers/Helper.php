<?php

if (!function_exists('send_sms')) {

    function send_sms($mobile, $text, $isPriority = 0)
    {

        $ch=curl_init();

        $post = array(
            'account' => '10014491', //número de usuario
            'apiKey' => 'alb31PO8iOcuH3N9VogINONY8I4Yai', //clave API del usuario
            'token' => '152d31601860bf4ddc7557728d70b7f5', // Token de usuario
            'toNumber' => $mobile, //número de destino
            'sms' => $text, // mensaje de texto
            'sendDate'=> time(), //fecha de envío del mensaje
            'isPriority' => $isPriority, //mensaje prioritario
        );

        $url = "https://api101.hablame.co/api/sms/v2.1/send/"; //endPoint: Primario
        curl_setopt ($ch,CURLOPT_URL,$url) ;
        curl_setopt ($ch,CURLOPT_POST,1);
        curl_setopt ($ch,CURLOPT_POSTFIELDS, $post);
        curl_setopt ($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt ($ch,CURLOPT_TIMEOUT, 20);
        $response= curl_exec($ch);
        curl_close($ch);
        $response= json_decode($response ,true) ;

        //La respuesta estará alojada en la variable $response

        /*if ($response["status"]== '1x000' ){
            echo 'El SMS se ha enviado exitosamente con el ID: '.$response["smsId"].PHP_EOL;
        } else {
            echo 'Ha ocurrido un error:'.$response["error_description"].'('.$response ["status" ]. ')'. PHP_EOL;
        }*/
    }
    /*function send_sms($cellphone, $body)
    {
        $url = 'https://api.hablame.co/sms/envio/';
        $data = array(
            'cliente' => 10014491,
            'api' => 'gEL4JmJYZByMezDP4vpyvKp5wfXnHL',
            'numero' => $cellphone,
            'sms' => $body,
            'fecha' => '',
            'referencia' => 'Favores',
        );
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = json_decode((file_get_contents($url, false, $context)), true);
        return $result;
    }*/
}
