<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function searchCurl($endpoint, $params = array(), $method = 'GET', $headers = array())
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

function sanitizeDomainName($domain) {
    return preg_replace("/[^a-zA-Z0-9-]/", "", $domain);
}

$domain = $_POST['domain-name'];
$tld = $_POST['domain-tld'];

$domain = str_replace('.co.za','', $domain);
//$domain = 'onerhosting';

$params = [
    'sld' => $domain,
    'tld' => $tld
];

$headers = [
    'Authorization: Bearer ' . $token
];

$result = searchCurl('domain/check', $params, 'GET', $headers);

// Decode the JSON response
$response = json_decode($result, true);

// Check if the response indicates domain availability
if (isset($response['isAvailable']) && $response['isAvailable'] === 'true') {
    //echo "<p >The domain $domain is available!</p>";

    echo '<div class="alert alart_style_four fade show mb20 d-flex justify-content-between" role="alert"><p style="font-size:2rem;"><i class="far fa-check-circle text-thm3 mt-4" style="font-size:2rem;"></i> The domain <b>'.$domain.'.'.$tld.'</b> is available!</p>
    <a href="create?domain='.$domain.'&type=main&tld='.$tld.'" class="ud-btn btn-thm mb25">Create website<i class="fal fa-arrow-right-long"></i></a>
  </div>';
} else {
    //echo "The domain $domain.$tld is not available.";

    echo '<div style="transform:translateX(-1rem)" class="alert alart_style_three fade show d-flex justify-content-between" role="alert"><p style="font-size:2rem;"><i class="far fa-times-circle text-thm7 mt-4" style="font-size:2rem;"></i> The domain <b>'.$domain.'.'.$tld.'</b> is not available!</p>
    <a href="create?domain='.$domain.'&type=main&tld='.$tld.'" class="ud-btn btn-thm mb25">Create website<i class="fal fa-arrow-right-long"></i></a>
  </div>';
}

?>