<?php


// Plesk XML API endpoint for adding a domain with hosting under a customer
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// XML request to add a domain with hosting under a customer
$requestXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.9.1">
  <webspace>
    <add>
      <gen_setup>
        <name>$domainName</name>
        <owner-login>$customerEmail</owner-login>
        <htype>vrt_hst</htype>
        <ip_address>$serverIpAddress</ip_address>
      </gen_setup>
      <hosting>
        <vrt_hst>
          <property>
            <name>ftp_login</name>
            <value>$customerUsername</value>
          </property>
          <property>
            <name>ftp_password</name>
            <value>$customerPassword</value>
          </property>
          <property>
            <name>www_root</name>
            <value>/httpdocs/site</value>
          </property>
        </vrt_hst>
      </hosting>
    </add>
  </webspace>
</packet>
EOT;

// Set cURL options
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: text/xml',
    'HTTP_AUTH_LOGIN: ' . $login,
    'HTTP_AUTH_PASSWD: ' . $password,
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXml);

// Perform the cURL request
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Process the API response
$xmlResponse = simplexml_load_string($response);
$result = $xmlResponse->webspace->add->result;

if ($result->status == 'ok') {
    $domain_success = true;
    $subscriptionID = (int)$result->id;
} else {
    $domain_success = false;
    echo "An error occurreds:domain " . $result->errtext;
}
?>
