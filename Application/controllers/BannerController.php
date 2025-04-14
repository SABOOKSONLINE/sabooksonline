<?php
// controllers/BannerController.php

require_once __DIR__ . '/../config/connection.php';
include_once 'models/BannerModel.php';

class BannerController
{
    private $bannerModel;

    public function __construct($conn)
    {
        $this->bannerModel = new BannerModel($conn);
    }

    public function loadBannersForPage($pageType)
    {
        $firstBanner = $this->bannerModel->getFirstBannerByType($pageType);
        $otherBanners = $this->bannerModel->getRemainingBannersByType($pageType);

        return [
            'first' => $firstBanner,
            'others' => $otherBanners
        ];
    }
}
?>

php code on how to display the banners
<!-- 
include_once 'controllers/BannerController.php';

$bannerController = new BannerController($conn);
$bannerData = $bannerController->loadBannersForPage('Home');

include 'partials/banner-carousel.php'; -->
