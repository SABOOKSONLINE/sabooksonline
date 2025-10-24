<?php

/**
 * Base layout for all views
 */

if (!isset($pageTitle)) $pageTitle = "SA Books Online";
if (!isset($content)) $content = "";

include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/nav.php";
?>

<body>
    <main>
        <?= $content ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <?php include __DIR__ . '/../includes/scripts.php'; ?>
</body>