<?php

ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

function infoCurl($endpoint, $params = array(), $method = 'GET', $headers = array())
{
    $ch = curl_init();

    $url = 'https://api.domains.co.za/api' . '/' . $endpoint;

    if ($method != 'POST') {
        $url .= '?' . http_build_query($params);
    } else {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $output = curl_exec($ch);

    if (curl_error($ch)) {
        $output = curl_error($ch);
    }

    curl_close($ch);

    return $output;
}

include_once 'authentication.php';

//$token = 'Your secret token from the auth function goes here.';

//Optional Params to filter by tld
$params = [
    'sld' => 'oner',
    'tld' => 'co.za'
];

$headers = [
    'Authorization: Bearer ' . $token
];

$result = infoCurl('domain', $params, 'GET', $headers);

print_r($result);
?>