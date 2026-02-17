<?php
session_start();
require_once __DIR__ . "/sessionAlerts.php";
require_once __DIR__ . "/../Core/Conn.php";
require_once __DIR__ . "/mail_alert.php";

if (!isset($_GET['type'], $_GET['return'])) {
    setAlert("warning", "Invalid request parameters!");
    header("Location: /admin");
    exit;
}

require_once __DIR__ . "/../Model/BookModel.php";
require_once __DIR__ . "/../Controller/UploadManagementController.php";

$controller = new UploadManagementController($conn);
$type = $_GET['type'];
$returnUrl = $_GET['return'];

function getEmailTemplate($title, $titleColor, $greeting, $mainMessage, $listItems, $ctaText, $ctaLink, $ctaColor)
{
    $listHtml = '';
    foreach ($listItems as $item) {
        $listHtml .= "<li>$item</li>";
    }

    return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: $titleColor;'>$title</h2>
            <p>$greeting</p>
            <p>$mainMessage</p>
            <ul>
                $listHtml
            </ul>
            <p style='margin: 20px 0;'>
                <a href='$ctaLink' style='background-color: $ctaColor; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;'>
                    $ctaText
                </a>
            </p>
            <p>Best regards,<br>SA Books Online Team</p>
        </div>
    ";
}

if ($type === "add-publisher") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $userId = trim($_POST['user_id'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');

        if (!$userId || !$email) {
            setAlert("danger", "User ID and Email are required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->addPublisher($userId, $email, $name)) {
            setAlert("success", "Publisher added successfully!");

            $link = "https://sabooksonline.co.za/dashboards";
            $subject = "Welcome to SA Books Online Publisher Program!";
            $message = getEmailTemplate(
                "Welcome to SA Books Online Publisher Program!",
                "#2c3e50",
                "Dear " . htmlspecialchars($name ?: 'Publisher') . ",",
                "Congratulations! Your account has been granted publisher privileges on SA Books Online. You can now:",
                [
                    "Upload and manage Hardcopies",
                ],
                "Access Publisher Dashboard",
                $link,
                "#3498db"
            );

            sendEmail($email, $subject, $message);
        } else {
            setAlert("danger", "Failed to add publisher. Email might already exist.");
        }
    }
} elseif ($type === "remove-publisher") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST['email'] ?? '');

        if (!$email) {
            setAlert("danger", "Email is required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->removePublisher($email)) {
            setAlert("success", "Publisher removed successfully!");

            $link = "https://sabooksonline.co.za/contact";
            $subject = "Publisher Account Status Update";
            $message = getEmailTemplate(
                "Publisher Account Status Update",
                "#e74c3c",
                "Dear Publisher,",
                "Your publisher privileges on SA Books Online have been removed. This means you will no longer be able to:",
                [
                    "Upload and manage Hardcopies",
                ],
                "Contact Support",
                $link,
                "#95a5a6"
            );

            sendEmail($email, $subject, $message);
        } else {
            setAlert("danger", "Failed to remove publisher.");
        }
    }
} elseif ($type === "toggle-publisher") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST['email'] ?? '');
        $canPublish = isset($_POST['can_publish']) ? (bool)$_POST['can_publish'] : false;

        if (!$email) {
            setAlert("danger", "Email is required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->togglePublisher($email, $canPublish)) {
            setAlert("success", "Publisher status updated!");

            $link = "https://sabooksonline.co.za/dashboards";

            if ($canPublish) {
                $subject = "Publisher Account Reactivated";
                $message = getEmailTemplate(
                    "Publisher Account Reactivated",
                    "#27ae60",
                    "Dear Publisher,",
                    "Good news! Your publisher account has been reactivated on SA Books Online. You can now resume:",
                    [
                        "Upload and manage Hardcopies",
                    ],
                    "Access Dashboard",
                    $link,
                    "#27ae60"
                );
            } else {
                $link = "https://sabooksonline.co.za/contact";
                $subject = "Publisher Account Temporarily Suspended";
                $message = getEmailTemplate(
                    "Publisher Account Temporarily Suspended",
                    "#e67e22",
                    "Dear Publisher,",
                    "Your publisher account on SA Books Online has been temporarily suspended. During this suspension:",
                    [
                        "You cannot manage hardcopy details because we don't have your book in stock",
                        "Existing listings remain visible but cannot be edited",
                    ],
                    "Contact Support",
                    $link,
                    "#e67e22"
                );
            }

            sendEmail($email, $subject, $message);
        } else {
            setAlert("danger", "Failed to update publisher status.");
        }
    }
}

header("Location: $returnUrl");
exit;
