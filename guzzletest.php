<?php

// https://reqres.in/

use GuzzleHttp\Client;
require 'vendor/autoload.php';

$client = new Client([
    //'base_uri' => 'https://reqres.in',
    'base_uri' => 'http://45.76.116.27/ARESAPI',
    'timeout'  => 2.0,
]);

function doPost($url, $body, $client)
{


    try {

        //$url = '/products/changed'; // '/api/users/'

        $r = $client->request('POST', $url, [
            'json' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'form_params' => $body

//            'form_params' => [
//                //'name' => 'my_name',
//                //'job' => 'my_job'
//                'since' => '2010-09-20T01:50:24.181Z'
//            ]
        ]);
        echo $r->getBody();
    }
    catch (\GuzzleHttp\Exception\GuzzleException $e) {
        echo $e->getMessage();
        echo $e->getTrace();
    }
}

doPost('/products/changed', ['since' => '2019-09-20T01:50:24.181Z'], $client);
