<?php

require_once __DIR__ . "/../Core/Model.php";

class CartModel extends Model
{
    /* -------------------------------------------
     *  TABLE CREATION
     * ------------------------------------------*/
    private function createCartTable(): bool
    {
        $columns = [
            "id"              => "INT AUTO_INCREMENT PRIMARY KEY",
            "user_id"         => "INT NOT NULL",
            "book_id"         => "INT NOT NULL",
            "cart_item_count" => "INT DEFAULT 1",
            "created_at"      => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at"      => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        return $this->createTable("book_cart", $columns);
    }

    /* -------------------------------------------
     *  GET USER CART ITEMS
     * ------------------------------------------*/
    public function getCartItemsByUserId(int $userId): array
    {
        $this->createCartTable();

        $sql = "SELECT 
                    bc.*,
                    hc.*,
                    b.COVER AS cover_image,
                    b.TITLE AS title,
                    b.DESCRIPTION AS description,
                    b.ISBN AS isbn,
                    b.CATEGORY AS category,
                    b.LANGUAGES AS language,
                    b.CONTENTID AS book_public_key,
                    b.PUBLISHER AS publisher_name,
                    b.DATEPOSTED AS date,
                    b.AUTHORS AS authors
                FROM book_cart AS bc
                JOIN posts AS b ON bc.book_id = b.ID
                LEFT JOIN book_hardcopy AS hc
                    ON hc.book_id = b.ID
                WHERE bc.user_id = ?
                ORDER BY bc.created_at DESC";

        return $this->fetchPrepared($sql, "i", [$userId]);
    }

    /* -------------------------------------------
     *  ADD / UPDATE ITEM
     * ------------------------------------------*/
    public function addItem(int $userId, int $bookId, int $qty = 1): bool
    {
        $this->createCartTable();

        $sqlCheck = "SELECT cart_item_count 
                 FROM book_cart 
                 WHERE user_id = ? AND book_id = ?";

        $existing = $this->fetchPrepared($sqlCheck, "ii", [$userId, $bookId]);

        if (!empty($existing)) {
            $sql = "UPDATE book_cart 
                SET cart_item_count = ?
                WHERE user_id = ? AND book_id = ?";

            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                return false;
            }

            $bound = $stmt->bind_param("iii", $qty, $userId, $bookId);
            if ($bound === false) {
                $stmt->close();
                return false;
            }

            $result = $stmt->execute();
            $stmt->close();
            return (bool) $result;
        }

        $sql = "INSERT INTO book_cart (user_id, book_id, cart_item_count)
            VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        $bound = $stmt->bind_param("iii", $userId, $bookId, $qty);
        if ($bound === false) {
            $stmt->close();
            return false;
        }

        $result = $stmt->execute();
        $stmt->close();
        return (bool) $result;
    }

    /* -------------------------------------------
     *  UPDATE QUANTITY
     * ------------------------------------------*/
    public function updateItemCount(int $userId, int $bookId, int $qty): bool
    {
        $this->createCartTable();

        if ($qty <= 0) {
            return $this->removeItem($userId, $bookId);
        }

        $sql = "UPDATE book_cart
                SET cart_item_count = ?
                WHERE user_id = ? AND book_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $qty, $userId, $bookId);
        return $stmt->execute();
    }

    /* -------------------------------------------
     *  REMOVE SINGLE ITEM
     * ------------------------------------------*/
    public function removeItem(int $userId, int $bookId): bool
    {
        $this->createCartTable();

        $sql = "DELETE FROM book_cart
                WHERE user_id = ? AND book_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $bookId);
        return $stmt->execute();
    }

    /* -------------------------------------------
     *  COUNT TOTAL ITEMS
     * ------------------------------------------*/
    public function countItems(int $userId): int
    {
        $this->createCartTable();

        $sql = "SELECT SUM(cart_item_count) AS total
                FROM book_cart
                WHERE user_id = ?";

        $data = $this->fetchPrepared($sql, "i", [$userId]);

        return (int)($data[0]["total"] ?? 0);
    }

    /* -------------------------------------------
     *  CLEAR CART
     * ------------------------------------------*/
    public function clearCart(int $userId): bool
    {
        $this->createCartTable();

        $sql = "DELETE FROM book_cart WHERE user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
