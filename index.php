<?php

require 'vendor/autoload.php';

$client = new GuzzleHttp\Client([
    'base_uri' => 'https://jsonplaceholder.typicode.com'
//    'base_uri' => 'https://google.com'
]);

$response = $client->request('GET', '/posts/1');

if($response->getStatusCode() == 200)
    echo $response->getBody();
else {
    echo 'fail';
}


/*
// create curl resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "https://jsonplaceholder.typicode.com/posts");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

echo $output;

// close curl resource to free up system resources
curl_close($ch);

*/
?>