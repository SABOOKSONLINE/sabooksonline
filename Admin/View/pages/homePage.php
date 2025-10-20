<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/cards/bkCard.php";
include __DIR__ . "/../layouts/tables/bTable.php";
include __DIR__ . "/../layouts/banners/stickyBanner.php";
include __DIR__ . "/../layouts/banners/mBanner.php";
include __DIR__ . "/../layouts/banners/popBanner.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Pages - Home";
ob_start();

renderHeading(
    "Pages",
    "Manage and update featured sections on the home page.",
);

renderAlerts();


$listings = $data["listings"];
$allBooks = $data["books"];
$banners = $data["banners"];

renderSectionHeader(
    "Pop-up Banner",
    "",
);

echo renderPopupBannerAdminUI($banners["popup_banners"], $allBooks);

renderSectionHeader(
    "Sticky Banner",
    "",
);

echo renderStickyBannerSlider($banners["sticky_banners"]);

renderSectionHeader(
    "Page Banner",
    "",
);

echo renderImageCarouselBanner($banners["page_banners"], "featuredBookCarousel");

$booksBySection = [];
foreach ($listings as $book) {
    $section = $book['section'];
    if (!isset($booksBySection[$section])) {
        $booksBySection[$section] = [];
    }
    $booksBySection[$section][] = $book;
}

$headers = ["Cover", "Title", "Public Key", "Publisher Name"];

foreach ($booksBySection as $sectionName => $books) {
    renderSectionHeader(
        $sectionName,
        "",
    );

    renderBookCards($books, false, $sectionName);
    echo renderBookTable($headers, $allBooks, $sectionName);
}

?>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
