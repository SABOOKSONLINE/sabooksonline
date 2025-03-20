<?php
// Define the EPP XML request
$eppRequest = '
    <?xml version="1.0" encoding="UTF-8"?>
    <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
        <command>
            <login>
                <clID>your_client_id</clID>
                <pw>your_password</pw>
                <options>
                    <version>1.0</version>
                    <lang>en</lang>
                </options>
            </login>
            <clTRID>12345-XYZ</clTRID>
        </command>
    </epp>
';

// Define the EPP server details
$eppServer = 'epp.zarc.net.za';
$eppPort = 700;

// Initialize cURL session
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "ssl://{$eppServer}:{$eppPort}");
curl_setopt($curl, CURLOPT_PORT, $eppPort);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $eppRequest);

// Execute cURL session
$response = curl_exec($curl);

// Check for cURL errors
if ($response === false) {
    echo 'cURL Error: ' . curl_error($curl);
} else {
    echo 'EPP Response: ' . $response;
}

// Close cURL session
curl_close($curl);
?>
