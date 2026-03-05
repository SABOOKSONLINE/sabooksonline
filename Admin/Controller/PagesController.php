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

        // Ensure section table exists
        $this->bookModel->createBookListingSections();
    }

    public function pages(): void
    {
        $allBooks = $this->bookModel->getAllBooks();
        $listings = $this->bookModel->getBooksListings();

        // Academic-specific data
        if (!$this->bookModel->academicListingsTableExists()) {
            $this->bookModel->createAcademicListingsTable();
        }

        $academicListings = $this->bookModel->getAcademicListingsWithBooks();
        $academicAllBooks = $this->bookModel->getAllAcademicBooks();

        // Book listing sections
        $sections = $this->bookModel->getBookListingSections();

        $stickyBanners = $this->bannerModel->getStickyBanners();
        $pageBanners = $this->bannerModel->getPageBanner();
        $popupBanners = $this->bannerModel->getPopupBanners();

        $this->render("homePage", [
            "sections" => $sections,
            "listings" => $listings,
            "books" => $allBooks,
            "academic_listings" => $academicListings,
            "academic_books" => $academicAllBooks,
            "banners" => [
                "sticky_banners" => $stickyBanners,
                "page_banners" => $pageBanners,
                "popup_banners" => $popupBanners
            ]
        ]);
    }

    public function books(): void
    {
        $books = $this->bookModel->getBooksTable();

        $this->render("books", [
            "books" => $books
        ]);
    }

    /** ---------------- LISTINGS ---------------- */

    public function addListing(string $publicKey, string $category): int
    {
        return $this->bookModel->addListing($publicKey, $category);
    }

    public function deleteListing(string $publicKey): int
    {
        return $this->bookModel->deleteListing($publicKey);
    }

    /** ---------------- BANNERS ---------------- */

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

    public function deletePopupBanner($id): int
    {
        return $this->bannerModel->removePopupBanner($id);
    }

    /** ---------------- ACADEMIC LISTINGS ---------------- */

    public function addAcademicListing(int $bookId, string $publicKey): int
    {
        return $this->bookModel->addAcademicListing($bookId, $publicKey);
    }

    public function getAcademicListings(): array
    {
        return $this->bookModel->getAcademicListings();
    }

    public function getAcademicListingById(int $id): array
    {
        return $this->bookModel->getAcademicListingById($id);
    }

    public function updateAcademicListing(int $id, int $bookId, string $publicKey): int
    {
        return $this->bookModel->updateAcademicListing($id, $bookId, $publicKey);
    }

    public function deleteAcademicListing(int $id): int
    {
        return $this->bookModel->deleteAcademicListing($id);
    }

    /** ---------------- BOOK LISTING SECTIONS ---------------- */

    public function getBookListingSections(): array
    {
        return $this->bookModel->getBookListingSections();
    }

    public function addBookListingSection(string $sectionName, int $orderIndex, string $cardType = "standard"): int
    {
        return $this->bookModel->addBookListingSection($sectionName, $orderIndex, $cardType);
    }

    public function updateBookListingSection(int $id, string $sectionName, int $orderIndex, string $cardType): int
    {
        return $this->bookModel->updateBookListingSection($id, $sectionName, $orderIndex, $cardType);
    }

    public function deleteBookListingSection(int $id): int
    {
        return $this->bookModel->deleteBookListingSection($id);
    }

    public function updateBookListingSectionOrder($id, $orderIndex)
    {
        return $this->bookModel->updateSectionOrder($id, $orderIndex);
    }
}
