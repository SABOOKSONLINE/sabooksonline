<?php

$apiUrl = 'https://jabu.onerserv.co.za:8443/enterprise/control/agent.php';
$apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';
$siteId = '825'; // Replace with the actual site ID
$destinationFolder = '/httpdocs';
$zipFileName = 'css.zip';

$externalZipURL = 'https://sabooksonline.co.za/sabo/css.zip'; // Replace with the actual external zip URL

// Download the zip file from the external link
$downloadedZipPath = '/path/to/temp/folder/css.zip';
file_put_contents($downloadedZipPath, file_get_contents($externalZipURL));

// Construct XML request for Plesk API
$xmlRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.7.0">
    <webspace>
        <upload-file>
            <filter>
                <site-id>$siteId</site-id>
            </filter>
            <file_name>$zipFileName</file_name>
            <destination>$destinationFolder</destination>
            <file>$downloadedZipPath</file>
        </upload-file>
        <exec>
            <filter>
                <site-id>$siteId</site-id>
            </filter>
            <script>
                <![CDATA[
                    unzip $destinationFolder/$zipFileName -d $destinationFolder
                ]]>
            </script>
        </exec>
    </webspace>
</packet>
XML;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $apiUrl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: text/xml',
    'KEY: ' . $apiKey
));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlRequest);

$response = curl_exec($curl);
curl_close($curl);

// Handle the API response
if ($response === false) {
    echo "Error: " . curl_error($curl);
} else {
    // Process the API response here
    echo "API Response:\n";
    echo $response;
}

// Clean up: Delete the downloaded zip file
unlink($downloadedZipPath);
?>
