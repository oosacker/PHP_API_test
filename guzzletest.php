<?php

// https://reqres.in/

use GuzzleHttp\Client;
require 'vendor/autoload.php';

$client = new Client([
    'base_uri' => 'https://reqres.in',
    'timeout'  => 2.0,
]);

try {
    $r = $client->request('POST', '/api/users', [
        'json' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ],
        'form_params' => [
            'name' => 'my_name',
            'job' => 'my_job'
        ]
    ]);
    echo $r->getBody();
}
catch (\GuzzleHttp\Exception\GuzzleException $e) {
    echo $e->getMessage();
}