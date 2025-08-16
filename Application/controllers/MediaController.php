<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class MediaController
{
    private $mediaModel;

    public function __construct($conn)
    {
        $this->mediaModel = new MediaModel($conn);
    }

    public function getAllMagazines(): array
    {
        try {
            return $this->mediaModel->selectMagazines();
        } catch (Exception $e) {
            error_log("Get all magazines error: " . $e->getMessage());
            return [];
        }
    }

    public function getMagazineById(string $publicKey): ?array
    {
        try {
            return $this->mediaModel->selectMagazineById($publicKey);
        } catch (Exception $e) {
            error_log("Get magazine by public key error: " . $e->getMessage());
            return null;
        }
    }

    // NEWSPAPER METHODS
    public function getAllNewspapers(): array
    {
        try {
            return $this->mediaModel->selectNewspapers();
        } catch (Exception $e) {
            error_log("Get all newspapers error: " . $e->getMessage());
            return [];
        }
    }

    public function getNewspaperById(string $publicKey): ?array
    {
        try {
            return $this->mediaModel->selectNewspaperById($publicKey);
        } catch (Exception $e) {
            error_log("Get newspaper by public key error: " . $e->getMessage());
            return null;
        }
    }
}
