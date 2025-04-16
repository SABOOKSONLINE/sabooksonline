<?php
class LibraryModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCategoriesWithCounts() {
        $sql = "SELECT * FROM category ORDER BY category";
        $result = mysqli_query($this->conn, $sql);
        $categories = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $row['number_rows'] = $this->getBookCountByCategory($row['CATEGORY']);
            $categories[] = $row;
        }

        return $categories;
    }

    private function getBookCountByCategory($category): mixed {
        $sql = "SELECT COUNT(*) as count FROM posts WHERE CATEGORY = ? AND STATUS = 'active'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }

    public function getFilteredBooks($category = null, $search = null) {
        $sql = "SELECT * FROM posts WHERE STATUS = 'active'";
        $params = [];
        $types = "";

        if ($category) {
            $sql .= " AND CATEGORY = ?";
            $params[] = $category;
            $types .= "s";
        }

        if ($search) {
            $sql .= " AND (TITLE LIKE ? OR PUBLISHER LIKE ? OR AUTHOR LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types .= "sss";
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
