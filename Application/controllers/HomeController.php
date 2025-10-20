<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Config/connection.php";
require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../models/BannersModel.php";

class HomeController extends Controller
{

    private BannersModel $bannersModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->bannersModel = new BannersModel($conn);
    }

    public function banners(): array
    {
        $stickyBanners = $this->bannersModel->getStickyBanners();
        $pageBanners = $this->bannersModel->getPageBanner();
        $popupBanners = $this->bannersModel->getPopupBanners();

        return [
            "banners" => [
                "sticky_banners" => $stickyBanners,
                "page_banners" => $pageBanners,
                "popup_banners" => $popupBanners
            ]
        ];
    }
}
