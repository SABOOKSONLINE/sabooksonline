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

    public function insertMagazine(array $data): bool
    {
        try {
            $result = $this->mediaModel->insertMagazine($data);
            if (!$result) {
                error_log("Insert failed - Model returned false");
            }
            return $result;
        } catch (Exception $e) {
            error_log("Magazine insert error: " . $e->getMessage());
            return false;
        }
    }

    public function updateMagazine(array $data): bool
    {
        try {
            $result = $this->mediaModel->updateMagazine($data);
            if (!$result) {
                error_log("Update failed - Model returned false");
            }
            return $result;
        } catch (Exception $e) {
            error_log("Magazine update error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteMagazine(int $id): bool
    {
        try {
            $result = $this->mediaModel->deleteMagazine($id);
            if (!$result) {
                error_log("Delete failed - Model returned false");
            }
            return $result;
        } catch (Exception $e) {
            error_log("Magazine delete error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllMagazines($publisher_id): array
    {
        try {
            return $this->mediaModel->selectMagazines($publisher_id);
        } catch (Exception $e) {
            error_log("Get all magazines error: " . $e->getMessage());
            return [];
        }
    }

    public function getMagazineById(int $id, $publisher_id): ?array
    {
        try {
            return $this->mediaModel->selectMagazineById($id, $publisher_id);
        } catch (Exception $e) {
            error_log("Get magazine by ID error: " . $e->getMessage());
            return null;
        }
    }
}
