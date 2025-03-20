<?php

$apiKey = 'AIzaSyAjMV74EvcWHUwlYrHUIiXM9VVb0LXZzho'; // Replace with your own Google Maps API key

// Prepare the request URL
$baseURL = 'https://www.googleapis.com/geolocation/v1/geolocate?key=' . $apiKey;

// Send the HTTP request
$ch = curl_init($baseURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
$response = curl_exec($ch);
curl_close($ch);

// Parse the JSON response
$data = json_decode($response);

// Extract the latitude and longitude
$latitude = null;
$longitude = null;

if ($data && isset($data->location)) {
    $latitude = $data->location->lat;
    $longitude = $data->location->lng;
}

// Output the results
if ($latitude && $longitude) {
   // echo "Latitude: $latitude, Longitude: $longitude";
} else {
    echo 'Unable to retrieve the current user location.';
}

?>
