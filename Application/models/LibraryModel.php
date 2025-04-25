<?php

/**
 * Class LibraryModel
 *
 * Handles operations related to book categories and filtered book retrieval from the database.
 */
class LibraryModel
{
    /**
     * @var mysqli $conn MySQLi database connection instance
     */
    private $conn;

    /**
     * Constructor
     *
     * @param mysqli $conn MySQLi connection
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Retrieves all book categories along with the number of active books in each.
     *
     * @return array An array of categories with book counts
     */
    public function getCategoriesWithCounts(): array
    {
        $sql = "SELECT * FROM category ORDER BY category";
        $result = mysqli_query($this->conn, $sql);

        $categories = [];

        while ($row = mysqli_fetch_assoc($result)) {
            // Append book count to each category row
            $row['number_rows'] = $this->getBookCountByCategory($row['CATEGORY']);
            $categories[] = $row;
        }

        return $categories;
    }

    /**
     * Get the number of active books in a specific category.
     *
     * @param string $category The name of the category
     * @return int Number of active books in the category
     */
    private function getBookCountByCategory(string $category): int
    {
        $sql = "SELECT COUNT(*) as count FROM posts WHERE CATEGORY = ? AND STATUS = 'active'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }

    /**
     * Retrieves books based on optional filters: category and search keyword.
     *
     * @param string|null $category Optional category filter
     * @param string|null $search Optional keyword search (title, publisher, author)
     * @return array List of matching books
     */
    public function getFilteredBooks(?string $category = null, ?string $search = null): array
    {
        $sql = "SELECT * FROM posts WHERE STATUS = 'active'";
        $params = [];
        $types = "";

        // Filter by category if provided
        if ($category) {
            $sql .= " AND CATEGORY = ?";
            $params[] = $category;
            $types .= "s";
        }

        // Filter by search keyword in title, publisher, or author
        if ($search) {
            $sql .= " AND (TITLE LIKE ? OR PUBLISHER LIKE ? OR AUTHOR LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types .= "sss";
        }

        $stmt = $this->conn->prepare($sql);

        // Bind dynamic parameters if any
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        // Return all matched results as associative array
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
