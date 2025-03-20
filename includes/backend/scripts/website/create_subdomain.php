<?php

// Plesk API endpoint
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// SOAP request XML
$xml = <<<EOT
<packet>
    <subdomain>
        <add>
            <parent>$domainName</parent>
            <name>$subdomainName</name>
            <home>/var/www/vhosts/$domainName/$userkey</home>
        </add>
    </subdomain>
</packet>
EOT;

// Set the SOAP request headers
$headers = [
    'Content-Type: text/xml',
    'HTTP_PRETTY_PRINT: TRUE',
    'HTTP_AUTH_LOGIN: ' . $login,
    'HTTP_AUTH_PASSWD: ' . $password,
    'HTTP_X-API-Key: ' . $apiKey,
];

// Perform the SOAP request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
$response = curl_exec($ch);
curl_close($ch);

// Process the API response
$xmlResponse = simplexml_load_string($response);
$result = $xmlResponse->subdomain->add->result;

if ($result->status == 'ok') {

    $domain_success = true;
    $subscriptionID = (int)$result->id;
    //$subscriptionID = 32;

    //echo $subscriptionID;

    //$webspaceID = (int)$xml->webspace->get->result->data->gen_info->id;
   // echo "Webspace ID for $domainName: $webspaceID";

}else{

    $domain_success = false;
   echo "Sub domain: " . $result->errtext;
    //echo $subdomainName;

}

?>