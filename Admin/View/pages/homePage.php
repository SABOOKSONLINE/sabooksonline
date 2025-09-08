<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/cards/bkCard.php";

$title = "Pages - Home";
ob_start();

renderHeading(
    "Dashboard Pages",
    "",
);
// echo "<pre>";
// print_r($data["listings"]);
// echo "</pre>";
?>

<?php
$listings = $data["listings"];

$booksBySection = [];
foreach ($listings as $book) {
    $section = $book['section'];
    if (!isset($booksBySection[$section])) {
        $booksBySection[$section] = [];
    }
    $booksBySection[$section][] = $book;
}

foreach ($booksBySection as $sectionName => $books) {
    renderSectionHeader(
        $sectionName,
        ""
    );

    echo '<div class="row">';
    renderBookCards($books);
    echo '</div>';
}
?>


<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
