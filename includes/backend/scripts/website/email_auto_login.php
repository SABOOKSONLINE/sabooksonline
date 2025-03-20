<?php
// Plesk webmail auto-login URL
$pleskWebmailUrl = 'https://jabu.onerserv.co.za:8443/enterprise/control/panel/app/webmail?from=leftMenu&secureLogin=true&context=mail';

// Replace with the actual Plesk server URL

// Username and password (for demonstration purposes)
$username = 'emmanuel@sabooksonline.co.za';
$password = '!Emmanuel@1632';

// Construct auto-login URL
$autoLoginUrl = $pleskWebmailUrl . '&login_name=' . urlencode($username) . '&passwd=' . urlencode($password);

echo 'Auto-login URL: ' . $autoLoginUrl;
?>
