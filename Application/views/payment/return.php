<?php
// You can verify the transaction again here if needed, but PayFast already notifies you via notify_url

echo "<h2>Thank you!</h2>";
echo "<p>Your payment was processed successfully. You may now download your book or view your order.</p>";

// Optionally redirect after a few seconds
// header("refresh:5;url=/user/dashboard.php");
