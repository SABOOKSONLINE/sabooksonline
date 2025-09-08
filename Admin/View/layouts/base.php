<?php

if (!isset($title)) $title = "Admin Dashboard";
if (!isset($content)) $content = "";
?>

<!DOCTYPE html>
<html lang="en">

<?php include __DIR__ . "/../includes/head.php"; ?>

<body>
    <?php include __DIR__ . "/../includes/nav.php"; ?>

    <main class="main">
        <section>
            <div class="container-fluid">
                <div class="row">
                    <?php include __DIR__ . "/../includes/left_bar.php"; ?>
                    <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . "/../includes/scripts.php"; ?>
</body>

</html>