<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class CreatorModel
{

    private $conn;
    private $name;
    private $date;
    private $email;
    private $website;
    private $contentId;
    private $description;
    private $cover;
    private $facebook;
    private $instagram;
    private $twitter;
    private $type;
    private $contentData;

    // Constructor: Initialize database connection
    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function getCreatorByContentId($contentId)
    {

        $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = ? OR ADMIN_NAME = ?";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $contentId, $contentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!mysqli_num_rows($result)) {
            return null;
        }
        while ($creatorData = mysqli_fetch_assoc($result)) {
            return $this->formatData($creatorData);
        }
    }

    private function formatData(array $creatorData): self
    {
        $this->name = ucwords($creatorData['ADMIN_NAME'] ?? '');
        $this->date = ucwords($creatorData['ADMIN_DATE'] ?? '');
        $this->email = ucwords($creatorData['ADMIN_EMAIL'] ?? '');
        $this->website = ucwords($creatorData['ADMIN_WEBSITE'] ?? '');
        $this->contentId = $creatorData['ADMIN_USERKEY'];
        $this->description = ucwords($creatorData['ADMIN_BIO'] ?? '');
        $this->cover = $creatorData['ADMIN_PROFILE_IMAGE'] ?? null;
        $this->facebook = strtolower($creatorData['ADMIN_FACEBOOK'] ?? '');
        $this->instagram = strtolower($creatorData['ADMIN_INSTAGRAM'] ?? '');
        $this->twitter = strtolower($creatorData['ADMIN_TWITTER'] ?? '');
        $this->type = ucwords($creatorData['ADMIN_TYPE'] ?? '');
        $this->contentData = ucwords($creatorData['ADMIN_USERKEY'] ?? '');

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function getContentId(): ?string
    {
        return $this->contentId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getContentData(): ?string
    {
        return $this->contentData;
    }
}
