<?php

$apiKey = 'AIzaSyAjMV74EvcWHUwlYrHUIiXM9VVb0LXZzho'; // Replace with your own Google Maps API key

$address = $reg_address; // Replace with the address you want to convert

// Prepare the request URL
$baseURL = 'https://maps.googleapis.com/maps/api/geocode/json?';
$params = [
    'address' => urlencode($address),
    'key' => $apiKey
];
$requestURL = $baseURL . http_build_query($params);

// Send the HTTP request
$response = file_get_contents($requestURL);

// Parse the JSON response
$data = json_decode($response);

// Extract the latitude and longitude
$latitude = null;
$longitude = null;

if ($data && $data->status === 'OK' && isset($data->results[0])) {
    $latitude = $data->results[0]->geometry->location->lat;
    $longitude = $data->results[0]->geometry->location->lng;
}

// Output the results
if ($latitude && $longitude) {
    //echo "Latitude: $latitude, Longitude: $longitude";
} else {
    //echo '<p class="alert alert-warning mt-4">We could not find your provided address on google, please type and select the suggested address.</p>';
}

?>
