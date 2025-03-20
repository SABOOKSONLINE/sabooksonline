<?php
require 'includes/composer/vendor/autoload.php';

use GeoIp2\Database\Reader;

// Path to your GeoLite2 City database
$geoLite2DbPath = '/path/to/GeoLite2-City.mmdb';

// Initialize the reader
$reader = new Reader($geoLite2DbPath);

// Get the user's IP address
$userIp = $_SERVER['REMOTE_ADDR'];

try {
    // Perform the geolocation lookup
    $record = $reader->city($userIp);

    $country = $record->country->name;
    $province = $record->mostSpecificSubdivision->name;
    $city = $record->city->name;

    echo "Country: $country<br>";
    echo "Province: $province<br>";
    echo "City: $city<br>";

} catch (Exception $e) {
    // Handle the error if the IP address is not found in the database
    echo 'Location could not be determined: ', $e->getMessage();
}
?>
