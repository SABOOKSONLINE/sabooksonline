<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/BookModel.php";
require_once __DIR__ . "/../Model/BannersModel.php";

class PagesController extends Controller
{
    private BookModel $bookModel;
    private BannerModel $bannerModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->bookModel = new BookModel($conn);
        $this->bannerModel = new BannerModel($conn);
    }

    public function pages(): void
    {
        $allBooks = $this->bookModel->getAllBooks();
        $listings = $this->bookModel->getBooksListings();
        $stickyBanners = $this->bannerModel->getStickyBanners();
        $pageBanners = $this->bannerModel->getPageBanner();
        $popupBanners = $this->bannerModel->getPopupBanners();

        $this->render("homePage", [
            "listings" => $listings,
            "books" => $allBooks,
            "banners" => [
                "sticky_banners" => $stickyBanners,
                "page_banners" => $pageBanners,
                "popup_banners" => $popupBanners
            ]
        ]);
    }

    public function addListing(string $publicKey, string $category): int
    {
        return $this->bookModel->addListing($publicKey, $category);
    }

    public function deleteListing(string $publicKey): int
    {
        return $this->bookModel->deleteListing($publicKey);
    }

    public function addStickyBanner($data): int
    {
        return $this->bannerModel->addStickyBanner($data);
    }

    public function deleteStickyBanner($id): int
    {
        return $this->bannerModel->removeStickyBanner($id);
    }

    public function addPageBanner($data): int
    {
        return $this->bannerModel->addPageBanner($data);
    }

    public function deletePageBanner($id): int
    {
        return $this->bannerModel->removePageBanner($id);
    }

    public function addPopupBanner($data): int
    {
        return $this->bannerModel->addPopupBanner($data);
    }

    public function books(): void
    {
        $books = $this->bookModel->getBooksTable();
        
        $this->render("books", [
            "books" => $books
        ]);
    }

    public function deletePopupBanner($id): int
    {
        return $this->bannerModel->removePopupBanner($id);
    }
}
