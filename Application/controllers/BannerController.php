<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BannerController
{
    private $bannerModel;

    public function __construct($conn)
    {
        $this->bannerModel = new BannerModel($conn);
    }

    public function renderBanner($pageType)
    {
        $banners = $this->bannerModel->getBannersByType($pageType);

        if ($banners) {
            include __DIR__ . "/../views/includes/banner.php";
        } else {
            echo "No banner found.";
        }
    }
}
?>

<!-- php code on how to display the banners -->

<!-- include_once 'controllers/BannerController.php';

$bannerController = new BannerController($conn);
$bannerData = $bannerController->loadBannersForPage('Home');

include 'partials/banner-carousel.php'; -->