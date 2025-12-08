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

    // --------------------------
    // DELIVERY ADDRESS METHODS
    // --------------------------

    /**
     * Create delivery_addresses table if not exists
     */
    private function createDeliveryAddressTable(): bool
    {
        $columns = [
            "id"            => "INT AUTO_INCREMENT PRIMARY KEY",
            "user_id"       => "INT NOT NULL",
            "company"       => "VARCHAR(255) DEFAULT NULL",
            "full_name"     => "VARCHAR(255) NOT NULL",
            "phone"         => "VARCHAR(50) NOT NULL",
            "email"         => "VARCHAR(255) NOT NULL",
            "street_address" => "VARCHAR(255) NOT NULL",
            "street_address2" => "VARCHAR(255) DEFAULT NULL",
            "delivery_type" => "ENUM('business','residential') DEFAULT 'residential'",
            "local_area"    => "VARCHAR(255) NOT NULL",
            "zone"          => "VARCHAR(255) NOT NULL",
            "postal_code"   => "VARCHAR(20) NOT NULL",
            "country"       => "VARCHAR(5) DEFAULT 'ZA'",
            "created_at"    => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at"    => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        return $this->createTable("delivery_addresses", $columns);
    }

    /**
     * Save or update a delivery address for a user
     */
    public function saveDeliveryAddress(int $userId, array $data): bool
    {
        $this->createDeliveryAddressTable();

        $company       = $data['company'] ?? null;
        $full_name     = $data['full_name'] ?? '';
        $phone         = $data['phone'] ?? '';
        $email         = $data['email'] ?? '';
        $street        = $data['street_address'] ?? '';
        $street2       = $data['street_address2'] ?? null;
        $delivery_type = $data['delivery_type'] ?? 'residential';
        $local_area    = $data['local_area'] ?? '';
        $zone          = $data['zone'] ?? '';
        $postal_code   = $data['postal_code'] ?? '';
        $country       = $data['country'] ?? 'ZA';

        $existing = $this->getDeliveryAddress($userId);

        if ($existing) {
            $sql = "UPDATE delivery_addresses
                SET company=?, full_name=?, phone=?, email=?, street_address=?, street_address2=?, delivery_type=?, local_area=?, zone=?, postal_code=?, country=?
                WHERE user_id=?";

            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) return false;

            $stmt->bind_param(
                "sssssssssssi",
                $company,
                $full_name,
                $phone,
                $email,
                $street,
                $street2,
                $delivery_type,
                $local_area,
                $zone,
                $postal_code,
                $country,
                $userId
            );

            $result = $stmt->execute();
            $stmt->close();
            return (bool) $result;
        } else {
            $sql = "INSERT INTO delivery_addresses (user_id, company, full_name, phone, email, street_address, street_address2, delivery_type, local_area, zone, postal_code, country)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) return false;

            $stmt->bind_param(
                "isssssssssss",
                $userId,
                $company,
                $full_name,
                $phone,
                $email,
                $street,
                $street2,
                $delivery_type,
                $local_area,
                $zone,
                $postal_code,
                $country
            );

            $result = $stmt->execute();
            $stmt->close();
            return (bool) $result;
        }
    }


    /**
     * Get a delivery address for a user
     */
    public function getDeliveryAddress(int $userId): ?array
    {
        $this->createDeliveryAddressTable();

        $sql = "SELECT * FROM delivery_addresses WHERE user_id = ?";
        $result = $this->fetchPrepared($sql, "i", [$userId]);

        return $result[0] ?? null;
    }

    /**
     * Remove a delivery address for a user
     */
    public function removeDeliveryAddress(int $userId): bool
    {
        $this->createDeliveryAddressTable();

        $sql = "DELETE FROM delivery_addresses WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
