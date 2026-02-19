<?php
if (session_status() === PHP_SESSION_NONE) {
    $cookieDomain = ".sabooksonline.co.za";
    session_set_cookie_params(0, '/', $cookieDomain);
    session_start();
}

if (empty($_SESSION['ADMIN_USERKEY'])) {
    header("Location: /login");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SABO | Dashboard</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="/public/images/sabo_favicon (144x144).svg" type="image/svg+xml">
    <link rel="apple-touch-icon" sizes="72x72" href="/public/images/sabo_favicon (72x72).png">
    <link rel="apple-touch-icon" sizes="114x114" href="/public/images/sabo_favicon (114x114).png">
    <link rel="apple-touch-icon" sizes="144x144" href="/public/images/sabo_favicon (144x144).png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Mono:wght@400;500;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/Dashboard/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Dashboard/assets/css/all.css">
    <link rel="stylesheet" href="/Dashboard/assets/css/all.min.css">

    <link rel="stylesheet" href="/Dashboard/assets/css/style.css">
    <link rel="stylesheet" href="/Dashboard/assets/css/responsive.css">
    <script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-gradient: linear-gradient(135deg, #434343 0%, #000000 100%);
        }

        .stat-card-enhanced {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .stat-card-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-gradient, var(--primary-gradient));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .stat-card-enhanced:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            border-color: transparent;
        }

        .stat-card-enhanced:hover::before {
            transform: scaleX(1);
        }

        .stat-card-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
            background: var(--card-gradient, var(--primary-gradient));
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-card-value {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .stat-card-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card-change {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-card-change.positive {
            color: #10b981;
        }

        .stat-card-change.negative {
            color: #ef4444;
        }
    </style>

</head>