<?php


$postRequest = array(
    'since' => '2010-09-20T01:50:24.181Z'
);

$cURLConnection = curl_init('http://45.76.116.27/ARESAPI/products/changed');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
));

$apiResponse = curl_exec($cURLConnection);
curl_close($cURLConnection);
$jsonArrayResponse = json_decode($apiResponse);

echo $apiResponse;

//use GuzzleHttp\Exception\GuzzleException;
//
//require 'vendor/autoload.php';
//
//$client = new GuzzleHttp\Client([
//    'base_uri' => 'http://45.76.116.27/ARESAPI',
////    'timeout'  => 2.0,
//]);
//
//try {
//    $headers = [
//        'Content-Type' => 'application/json',
//        'Accept' => 'application/json'
//    ];
//    $body = [
//        'since' => '2010-09-20T01:50:24.181Z'
//    ];
//    $response = $client->request('POST', '/products/changed', ['headers' =>$headers, 'body' => $body]);
//    $result = json_decode($response->getBody());
//
//    echo '<br>';
//    echo 'Body: ' . $result->body;
//
//} catch (GuzzleException $e) {
//    echo '<br> Could not load data from API';
//}


?>


