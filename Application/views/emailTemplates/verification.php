<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        table, td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            display: block;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #f0f3f6;
        }
        a {
            text-decoration: none;
        }
        @media screen and (max-width: 620px) {
            .container {
                width: 90% !important;
                padding: 20px !important;
            }
            .content {
                padding: 30px 15px !important;
            }
            .button {
                padding: 12px 20px !important;
                font-size: 15px !important;
            }
        }
    </style>
</head>
<body>

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f3f6; padding:30px 0;">
    <tr>
        <td align="center">
            <table class="container" width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:12px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); overflow:hidden;">

                <!-- Header / Logo -->
                <tr>
                    <td align="center" style="background-color:#fff; padding:30px;">
                        <img src="https://sabooksonline.co.za/public/images/sabo_logo.png" alt="SABooksOnline" width="140" style="display:block;">
                    </td>
                </tr>

                <!-- Main Content -->
                <tr>
                    <td class="content" style="padding:40px; text-align:center; color:#4a4a4a;">
                        <h2 style="margin:0 0 20px; font-size:28px; color:#222222; font-weight:700;">Verify Email</h2>
                        <p style="margin:0 0 30px; font-size:17px; line-height:1.6; color:#555555;">
                            Hi <?= htmlspecialchars($userName) ?>, thanks for signing up! A verification request was made by <?= htmlspecialchars($device) ?>. Please verify your email by clicking the button below:
                        </p>
                        <!-- Inline button for email-client compatibility -->
                        <a href="<?= htmlspecialchars($verificationLink) ?>"
                           style="display:inline-block; background-color:#e60338; color:#ffffff !important; padding:15px 30px; border-radius:8px; font-weight:600; font-size:16px; line-height:1.2; text-decoration:none !important;">
                            Verify Email
                        </a>
                        <p style="margin-top:40px; font-size:13px; color:#888888; line-height:1.5;">
                            If you didn’t sign up for this account or didn’t request this verification, please ignore this email. No action is required.
                        </p>
                    </td>
                </tr>

                <!-- Divider -->
                <tr>
                    <td style="padding:0 40px;">
                        <hr style="border:0; border-top:1px solid #e0e0e0; margin:0;">
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="background-color:#000000; padding:25px; font-size:12px; color:#ffffff;">
                        &copy; <?= date('Y') ?> SA Books Online. All rights reserved.<br>
                        68 Melville Rd, Illovo Point, Sandton<br>
                        Sent from SABooksOnline Website & Mobile App
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
