<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API test script</title>
</head>

<body>

<form action="/php-test-1/index.php" method="post" >
    <label for="id">Enter ID:</label>
    <input type="text" name="id" required>
    <button type="submit" value="Submit">Submit</button>
</form>

<?php
use GuzzleHttp\Exception\GuzzleException;
require 'vendor/autoload.php';

if(isset($_POST['id'])) {

    $client = new GuzzleHttp\Client([
        'base_uri' => 'https://jsonplaceholder.typicode.com'
    ]);

    $wanted = $_REQUEST['id'];

    try {
        $response = $client->request('GET', '/posts/' . $wanted);
        $result = json_decode($response->getBody());

        echo '<br>';
        echo 'Title: ' . $result->title . '<br>';
        echo 'Body: ' . $result->body;

    }
    catch (GuzzleException $e) {
        exit('fail');
    }
}
else {
    echo '<br> No data!';
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

</body>
</html>
