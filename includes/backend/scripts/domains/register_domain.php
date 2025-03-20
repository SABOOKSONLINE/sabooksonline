<?php

function domainsCurl($endpoint, $params = array(), $method = 'GET', $headers = array())
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

$params = [
    'sld' => $sld,
    'tld' => $tld,
    'registrantName' => $_SESSION['ADMIN_NAME'],
    'registrantEmail' => 'domains@sabooksonline.co.za',
    'registrantCountry' => 'ZA',
    'registrantProvince' => 'Gauteng',
    'registrantContactNumber' => '+27.894435389',
    'registrantPostalCode' => '2190',
    'registrantAddress1' => '68 Melville Road',
    'registrantAddress2' => 'Illovo Sandton',
    'registrantCity' => 'Johannesburg',
    'period' => '1',
    'ns1' => 'ns1.onerserv.co.za',
    'ns2' => 'ns2.onerserv.co.za',
];

$headers = [
    'Authorization: Bearer ' . $token
];

$result = domainsCurl('domain', $params, 'POST', $headers);

print_r($result);

//echo $domain_name;
?>