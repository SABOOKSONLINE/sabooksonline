<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class MediaModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function insertMagazine(array $data): bool
    {
        $sql = "INSERT INTO magazines (
            publisher_id, 
            title, 
            editor, 
            category, 
            issn, 
            price, 
            frequency, 
            language, 
            country, 
            publish_date, 
            description, 
            cover_image_path, 
            pdf_path,
            public_key
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "issssdssssssss",
            $data['publisher_id'],
            $data['title'],
            $data['editor'],
            $data['category'],
            $data['issn'],
            $data['price'],
            $data['frequency'],
            $data['language'],
            $data['country'],
            $data['publish_date'],
            $data['description'],
            $data['cover_image_path'],
            $data['pdf_path'],
            $data['public_key']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function updateMagazine(array $data): bool
    {
        $sql = "UPDATE magazines SET
            publisher_id = ?, 
            title = ?, 
            editor = ?, 
            category = ?, 
            issn = ?, 
            price = ?, 
            frequency = ?, 
            language = ?, 
            country = ?, 
            publish_date = ?, 
            description = ?, 
            cover_image_path = ?, 
            pdf_path = ?,
            public_key = ?
        WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "issssdssssssssi",
            $data['publisher_id'],
            $data['title'],
            $data['editor'],
            $data['category'],
            $data['issn'],
            $data['price'],
            $data['frequency'],
            $data['language'],
            $data['country'],
            $data['publish_date'],
            $data['description'],
            $data['cover_image_path'],
            $data['pdf_path'],
            $data['public_key'],
            $data['id']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function deleteMagazine(int $id): bool
    {
        $sql = "DELETE FROM magazines WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function selectMagazines(int $publisher_id): array
    {
        $sql = "SELECT * FROM magazines WHERE publisher_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_bind_param($stmt, "i", $publisher_id)) {
            throw new Exception("Bind failed: " . mysqli_stmt_error($stmt));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Get result failed: " . mysqli_stmt_error($stmt));
        }

        $magazines = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $magazines[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $magazines;
    }

    public function selectMagazineById(int $id, $publisher_id): ?array
    {
        $sql = "SELECT * FROM magazines WHERE id = ? AND publisher_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "is", $id, $publisher_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $magazine = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $magazine ?: null;
    }


    // NEWSPAPER METHODS
    public function insertNewspaper(array $data): bool
    {
        $sql = "INSERT INTO newspapers (
        title, 
        description, 
        cover_image_path, 
        pdf_path, 
        category, 
        publisher_id, 
        public_key, 
        price, 
        publish_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssssisds",
            $data['title'],
            $data['description'],
            $data['cover_image_path'],
            $data['pdf_path'],
            $data['category'],
            $data['publisher_id'],
            $data['public_key'],
            $data['price'],
            $data['publish_date']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function updateNewspaper(array $data): bool
    {
        $sql = "UPDATE newspapers SET
        title = ?, 
        description = ?, 
        cover_image_path = ?, 
        pdf_path = ?, 
        category = ?, 
        publisher_id = ?, 
        public_key = ?, 
        price = ?, 
        publish_date = ?
    WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssssisdsi",
            $data['title'],
            $data['description'],
            $data['cover_image_path'],
            $data['pdf_path'],
            $data['category'],
            $data['publisher_id'],
            $data['public_key'],
            $data['price'],
            $data['publish_date'],
            $data['id']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function deleteNewspaper(int $id): bool
    {
        $sql = "DELETE FROM newspapers WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function selectNewspapers(int $publisher_id): array
    {
        $sql = "SELECT * FROM newspapers WHERE publisher_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $publisher_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $newspapers = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $newspapers[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $newspapers;
    }

    public function selectNewspaperById(int $id, int $publisher_id): ?array
    {
        $sql = "SELECT * FROM newspapers WHERE id = ? AND publisher_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "ii", $id, $publisher_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $newspaper = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $newspaper ?: null;
    }
}
