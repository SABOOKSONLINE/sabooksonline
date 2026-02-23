<?php

require_once __DIR__ . "/../Core/Model.php";

class CartModel extends Model
{
    private function createCartTable(): bool
    {
        $columns = [
            "id"              => "INT AUTO_INCREMENT PRIMARY KEY",
            "user_id"         => "INT NOT NULL",
            "book_id"         => "VARCHAR(255) NOT NULL",
            "book_type"       => "ENUM('regular', 'academic') DEFAULT 'regular'",
            "cart_item_count" => "INT DEFAULT 1",
            "created_at"      => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at"      => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];
        $created = $this->createTable("book_cart", $columns);

        // Migrate existing data: add book_type column if table exists but column doesn't
        $this->migrateCartTable();

        return $created;
    }

    private function migrateCartTable(): void
    {
        // Check if book_type column exists by trying to describe the table
        try {
            $result = $this->conn->query("DESCRIBE book_cart");
            $columns = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $columns[] = $row['Field'];
                }
            }

            if (!in_array('book_type', $columns)) {
                // Add book_type column and migrate existing data
                $this->conn->query("ALTER TABLE book_cart ADD COLUMN book_type ENUM('regular', 'academic') DEFAULT 'regular' AFTER book_id");
            }

            // Check if book_id is INT and convert to VARCHAR
            $result = $this->conn->query("DESCRIBE book_cart");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['Field'] === 'book_id' && strpos(strtolower($row['Type']), 'int') !== false) {
                        $this->conn->query("ALTER TABLE book_cart MODIFY COLUMN book_id VARCHAR(255) NOT NULL");
                        break;
                    }
                }
            }
        } catch (Exception $e) {
            // Table might not exist yet, that's okay
            error_log("Cart table migration: " . $e->getMessage());
        }
    }

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

    private function createOrdersTable(): bool
    {
        $columns = [
            "id"                  => "INT AUTO_INCREMENT PRIMARY KEY",
            "user_id"             => "INT NOT NULL",
            "delivery_address_id" => "INT NOT NULL",
            "order_number"        => "VARCHAR(50) NOT NULL",
            "total_amount"        => "DECIMAL(10,2) NOT NULL DEFAULT 0.00",
            "shipping_fee"        => "DECIMAL(10,2) DEFAULT 0.00",
            "payment_method"      => "VARCHAR(50) DEFAULT NULL",
            "payment_status"      => "ENUM('pending','paid','failed','refunded') DEFAULT 'paid'",
            "order_status"        => "ENUM('pending','processing','packed','shipped','out_for_delivery','delivered','cancelled','returned') DEFAULT 'pending'",
            "tracking_number"     => "VARCHAR(255) DEFAULT NULL",
            "created_at"          => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at"          => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];
        return $this->createTable("orders", $columns);
    }

    private function createOrderItemsTable(): bool
    {
        $columns = [
            "id"          => "INT AUTO_INCREMENT PRIMARY KEY",
            "order_id"    => "INT NOT NULL",
            "book_id"     => "VARCHAR(255) NOT NULL",
            "book_type"   => "ENUM('regular', 'academic') DEFAULT 'regular'",
            "quantity"    => "INT NOT NULL DEFAULT 1",
            "unit_price"  => "DECIMAL(10,2) NOT NULL",
            "total_price" => "DECIMAL(10,2) NOT NULL",
            "created_at"  => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
        ];
        $created = $this->createTable("order_items", $columns);

        // Migrate existing data
        $this->migrateOrderItemsTable();

        return $created;
    }

    private function migrateOrderItemsTable(): void
    {
        // Check if book_type column exists by trying to describe the table
        try {
            $result = $this->conn->query("DESCRIBE order_items");
            $columns = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $columns[] = $row['Field'];
                }
            }

            if (!in_array('book_type', $columns)) {
                // Add book_type column and migrate existing data
                $this->conn->query("ALTER TABLE order_items ADD COLUMN book_type ENUM('regular', 'academic') DEFAULT 'regular' AFTER book_id");
            }

            // Check if book_id is INT and convert to VARCHAR
            $result = $this->conn->query("DESCRIBE order_items");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['Field'] === 'book_id' && strpos(strtolower($row['Type']), 'int') !== false) {
                        $this->conn->query("ALTER TABLE order_items MODIFY COLUMN book_id VARCHAR(255) NOT NULL");
                        break;
                    }
                }
            }
        } catch (Exception $e) {
            // Table might not exist yet, that's okay
            error_log("Order items table migration: " . $e->getMessage());
        }
    }

    public function getCartItemsByUserId(int $userId): array
    {
        $this->createCartTable();

        // Get regular books
        $sqlRegular = "SELECT bc.*, hc.*, b.COVER AS cover_image, b.TITLE AS title, b.DESCRIPTION AS description,
                       b.ISBN AS isbn, b.CATEGORY AS category, b.LANGUAGES AS language,
                       b.CONTENTID AS book_public_key, b.PUBLISHER AS publisher_name,
                       b.DATEPOSTED AS date, b.AUTHORS AS authors,
                       'regular' AS item_type
                FROM book_cart AS bc
                JOIN posts AS b ON bc.book_id = CAST(b.ID AS CHAR) AND bc.book_type = 'regular'
                LEFT JOIN book_hardcopy AS hc ON hc.book_id = b.ID
                WHERE bc.user_id = ?";

        // Get academic books (only hardcopy should be in cart, so use physical_book_price)
        $sqlAcademic = "SELECT bc.*, NULL AS hc_id, ab.physical_book_price AS hc_price, NULL AS hc_stock_count,
                       ab.cover_image_path AS cover_image, ab.title, ab.description,
                       ab.ISBN AS isbn, ab.subject AS category, ab.language,
                       ab.public_key AS book_public_key, NULL AS publisher_name,
                       ab.created_at AS date, ab.author AS authors,
                       'academic' AS item_type
                FROM book_cart AS bc
                JOIN academic_books AS ab ON bc.book_id = ab.public_key AND bc.book_type = 'academic'
                WHERE bc.user_id = ?";

        $regularBooks = $this->fetchPrepared($sqlRegular, "i", [$userId]);
        $academicBooks = $this->fetchPrepared($sqlAcademic, "i", [$userId]);

        // Combine and sort by created_at
        $allItems = array_merge($regularBooks, $academicBooks);
        usort($allItems, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $allItems;
    }

    public function addItem(int $userId, $bookId, int $qty = 1, string $bookType = 'regular'): bool
    {
        $this->createCartTable();
        $bookIdStr = (string)$bookId;
        $sqlCheck = "SELECT cart_item_count FROM book_cart WHERE user_id = ? AND book_id = ? AND book_type = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->bind_param("iss", $userId, $bookIdStr, $bookType);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing = $result->fetch_assoc();
        $stmt->close();

        if (!empty($existing)) {
            $sql = "UPDATE book_cart SET cart_item_count = ? WHERE user_id = ? AND book_id = ? AND book_type = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isss", $qty, $userId, $bookIdStr, $bookType);
            $stmt->execute();
            $stmt->close();
            return true;
        }
        $sql = "INSERT INTO book_cart (user_id, book_id, book_type, cart_item_count) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", $userId, $bookIdStr, $bookType, $qty);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function updateItemCount(int $userId, $bookId, int $qty, string $bookType = 'regular'): bool
    {
        $this->createCartTable();
        if ($qty <= 0) return $this->removeItem($userId, $bookId, $bookType);
        $bookIdStr = (string)$bookId;
        $sql = "UPDATE book_cart SET cart_item_count = ? WHERE user_id = ? AND book_id = ? AND book_type = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss", $qty, $userId, $bookIdStr, $bookType);
        return $stmt->execute();
    }

    public function updateItemCountByCartId(int $cartId, int $qty): bool
    {
        $this->createCartTable();
        if ($qty <= 0) return $this->removeItemByCartId($cartId);
        $sql = "UPDATE book_cart SET cart_item_count = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $qty, $cartId);
        return $stmt->execute();
    }

    public function removeItem(int $userId, $bookId, string $bookType = 'regular'): bool
    {
        $this->createCartTable();
        $bookIdStr = (string)$bookId;
        $sql = "DELETE FROM book_cart WHERE user_id = ? AND book_id = ? AND book_type = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $bookIdStr, $bookType);
        return $stmt->execute();
    }

    public function removeItemByCartId(int $cartId): bool
    {
        $this->createCartTable();
        $sql = "DELETE FROM book_cart WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $cartId);
        return $stmt->execute();
    }

    public function countItems(int $userId): int
    {
        $this->createCartTable();
        $sql = "SELECT SUM(cart_item_count) AS total FROM book_cart WHERE user_id = ?";
        $data = $this->fetchPrepared($sql, "i", [$userId]);
        return (int)($data[0]["total"] ?? 0);
    }

    public function clearCart(int $userId): bool
    {
        $this->createCartTable();
        $sql = "DELETE FROM book_cart WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    public function saveDeliveryAddress(int $userId, array $data): bool
    {
        $this->createDeliveryAddressTable();
        $existing = $this->getDeliveryAddress($userId);
        $company = $data['company'] ?? null;
        $full_name = $data['full_name'] ?? '';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? '';
        $street = $data['street_address'] ?? '';
        $street2 = $data['street_address2'] ?? null;
        $delivery_type = $data['delivery_type'] ?? 'residential';
        $local_area = $data['local_area'] ?? '';
        $zone = $data['zone'] ?? '';
        $postal_code = $data['postal_code'] ?? '';
        $country = $data['country'] ?? 'ZA';
        if ($existing) {
            $sql = "UPDATE delivery_addresses SET company=?, full_name=?, phone=?, email=?, street_address=?, street_address2=?, delivery_type=?, local_area=?, zone=?, postal_code=?, country=? WHERE user_id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssssssssssi", $company, $full_name, $phone, $email, $street, $street2, $delivery_type, $local_area, $zone, $postal_code, $country, $userId);
            return $stmt->execute();
        }
        $sql = "INSERT INTO delivery_addresses (user_id, company, full_name, phone, email, street_address, street_address2, delivery_type, local_area, zone, postal_code, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssssssssss", $userId, $company, $full_name, $phone, $email, $street, $street2, $delivery_type, $local_area, $zone, $postal_code, $country);
        return $stmt->execute();
    }

    public function updateDeliveryAddressById(int $addressId, array $data): bool
    {
        $this->createDeliveryAddressTable();
        $company = $data['company'] ?? null;
        $full_name = $data['full_name'] ?? '';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? '';
        $street = $data['street_address'] ?? '';
        $street2 = $data['street_address2'] ?? null;
        $delivery_type = $data['delivery_type'] ?? 'residential';
        $local_area = $data['local_area'] ?? '';
        $zone = $data['zone'] ?? '';
        $postal_code = $data['postal_code'] ?? '';
        $country = $data['country'] ?? 'ZA';

        $sql = "UPDATE delivery_addresses SET company=?, full_name=?, phone=?, email=?, street_address=?, street_address2=?, delivery_type=?, local_area=?, zone=?, postal_code=?, country=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssssssi", $company, $full_name, $phone, $email, $street, $street2, $delivery_type, $local_area, $zone, $postal_code, $country, $addressId);
        return $stmt->execute();
    }

    public function getDeliveryAddress(int $userId): ?array
    {
        $this->createDeliveryAddressTable();
        $sql = "SELECT * FROM delivery_addresses WHERE user_id = ?";
        $res = $this->fetchPrepared($sql, "i", [$userId]);
        return $res[0] ?? null;
    }

    public function removeDeliveryAddress(int $userId): bool
    {
        $this->createDeliveryAddressTable();
        $sql = "DELETE FROM delivery_addresses WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    public function createOrder(int $userId): ?int
    {
        $this->createOrdersTable();
        $this->createOrderItemsTable();
        $cartItems = $this->getCartItemsByUserId($userId);
        if (empty($cartItems)) return null;
        $address = $this->getDeliveryAddress($userId);
        if (!$address) return null;
        $orderNumber = "ORD-" . time() . "-" . rand(1000, 9999);
        $subtotal = 0;
        foreach ($cartItems as $item) $subtotal += ($item["hc_price"] ?? 0) * $item["cart_item_count"];
        $shippingFee = 60;
        $total = $subtotal + $shippingFee;
        $sql = "INSERT INTO orders (user_id, delivery_address_id, order_number, total_amount, shipping_fee) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisdd", $userId, $address["id"], $orderNumber, $total, $shippingFee);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();
        foreach ($cartItems as $item) {
            $price = $item["hc_price"] ?? 0;
            $qty = $item["cart_item_count"];
            $totalPrice = $price * $qty;
            $bookId = (string)$item["book_id"];
            $bookType = $item["item_type"] ?? $item["book_type"] ?? 'regular';
            $sqlItem = "INSERT INTO order_items (order_id, book_id, book_type, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtItem = $this->conn->prepare($sqlItem);
            $stmtItem->bind_param("issidd", $orderId, $bookId, $bookType, $qty, $price, $totalPrice);
            $stmtItem->execute();
            $stmtItem->close();
        }
        $this->clearCart($userId);
        return $orderId;
    }

    public function getOrderDetails(int $orderId): ?array
    {
        $this->createOrdersTable();
        $this->createOrderItemsTable();
        $this->createDeliveryAddressTable();
        $sql = "SELECT o.*, d.* FROM orders AS o JOIN delivery_addresses AS d ON o.delivery_address_id=d.id WHERE o.id=?";
        $order = $this->fetchPrepared($sql, "i", [$orderId]);
        if (empty($order)) return null;

        // Fetch items with book details in one query using JOINs
        $sqlItems = "SELECT 
            oi.*,
            COALESCE(b.TITLE, ab.title) AS title,
            COALESCE(b.AUTHORS, ab.author) AS author,
            COALESCE(b.COVER, ab.cover_image_path) AS cover,
            CASE 
                WHEN oi.book_type = 'academic' THEN '/cms-data/academic/covers/'
                ELSE '/cms-data/book-covers/'
            END AS cover_path
        FROM order_items oi
        LEFT JOIN posts b ON oi.book_id = CAST(b.ID AS CHAR) AND oi.book_type = 'regular'
        LEFT JOIN academic_books ab ON oi.book_id = ab.public_key AND oi.book_type = 'academic'
        WHERE oi.order_id = ?";
        $items = $this->fetchPrepared($sqlItems, "i", [$orderId]);
        return ["order" => $order[0], "items" => $items];
    }

    public function getOrders(int $userId): ?array
    {
        $this->createOrdersTable();
        $this->createOrderItemsTable();
        $this->createDeliveryAddressTable();

        // 1. Fetch all orders with their delivery addresses
        $sqlOrders = "SELECT o.*, d.* 
                  FROM orders AS o
                  JOIN delivery_addresses AS d 
                    ON o.delivery_address_id = d.id
                  WHERE o.user_id = ?
                  ORDER BY o.id DESC";

        $orders = $this->fetchPrepared($sqlOrders, "i", [$userId]);
        if (empty($orders)) return null;

        // Extract order IDs
        $orderIds = array_column($orders, 'id');
        if (empty($orderIds)) return null;

        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));

        // 2. Fetch all order items with book details in one query using JOINs
        $sqlItems = "SELECT 
        oi.*,
        COALESCE(b.TITLE, ab.title) AS title,
        COALESCE(b.AUTHORS, ab.author) AS author,
        COALESCE(b.COVER, ab.cover_image_path) AS cover,
        CASE 
            WHEN oi.book_type = 'academic' THEN '/cms-data/academic/covers/'
            ELSE '/cms-data/book-covers/'
        END AS cover_path
    FROM order_items oi
    LEFT JOIN posts b ON oi.book_id = CAST(b.ID AS CHAR) AND oi.book_type = 'regular'
    LEFT JOIN academic_books ab ON oi.book_id = ab.public_key AND oi.book_type = 'academic'
    WHERE oi.order_id IN ($placeholders)";

        $itemsData = $this->fetchPrepared($sqlItems, str_repeat('i', count($orderIds)), $orderIds);

        // 3. Group items by order_id
        $itemsByOrder = [];
        foreach ($itemsData as $item) {
            $itemsByOrder[$item['order_id']][] = $item;
        }

        // 4. Combine orders with their items
        $result = [];
        foreach ($orders as $order) {
            $result[] = [
                "order" => $order,
                "items" => $itemsByOrder[$order['id']] ?? []
            ];
        }

        return $result;
    }


    public function updateOrderTotals(int $orderId, ?float $totalAmount, ?float $shippingFee, ?string $paymentMethod): bool
    {
        $this->createOrdersTable();
        $order = $this->fetchPrepared("SELECT * FROM orders WHERE id=?", "i", [$orderId]);
        if (empty($order)) return false;

        $order = $order[0];
        $totalAmount = $totalAmount ?? $order['total_amount'];
        $shippingFee = $shippingFee ?? $order['shipping_fee'];
        $paymentMethod = $paymentMethod ?? $order['payment_method'];

        $stmt = $this->conn->prepare("
        UPDATE orders 
        SET total_amount=?, shipping_fee=?, payment_method=?
        WHERE id=?
    ");
        $stmt->bind_param("ddsi", $totalAmount, $shippingFee, $paymentMethod, $orderId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // ─── Collection Address Table ───────────────────────────────────────────────

private function createCollectionAddressTable(): bool
{
    $columns = [
        "id"                   => "INT UNSIGNED AUTO_INCREMENT PRIMARY KEY",
        "user_id"              => "INT NOT NULL",
        "nickname"             => "VARCHAR(100) NOT NULL COMMENT 'e.g. Home, Office'",
        "contact_name"         => "VARCHAR(150) NOT NULL",
        "contact_phone"        => "VARCHAR(20) NOT NULL",
        "contact_email"        => "VARCHAR(255) NOT NULL",
        "unit_number"          => "VARCHAR(20) DEFAULT NULL",
        "complex_name"         => "VARCHAR(150) DEFAULT NULL",
        "street_number"        => "VARCHAR(20) NOT NULL",
        "street_name"          => "VARCHAR(255) NOT NULL",
        "suburb"               => "VARCHAR(150) NOT NULL",
        "city"                 => "VARCHAR(150) NOT NULL",
        "province"             => "VARCHAR(100) NOT NULL",
        "postal_code"          => "VARCHAR(10) NOT NULL",
        "country_code"         => "CHAR(2) NOT NULL DEFAULT 'ZA'",
        "special_instructions" => "TEXT DEFAULT NULL",
        "is_default"           => "TINYINT(1) NOT NULL DEFAULT 0",
        "created_at"           => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
        "updated_at"           => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ];
    return $this->createTable("book_collection_addresses", $columns);
}

public function addCollectionAddress(int $userId, array $data): ?int
{
    $this->createCollectionAddressTable();

    $nickname             = $data['nickname'] ?? '';
    $contact_name         = $data['contact_name'] ?? '';
    $contact_phone        = $data['contact_phone'] ?? '';
    $contact_email        = $data['contact_email'] ?? '';
    $unit_number          = $data['unit_number'] ?? null;
    $complex_name         = $data['complex_name'] ?? null;
    $street_number        = $data['street_number'] ?? '';
    $street_name          = $data['street_name'] ?? '';
    $suburb               = $data['suburb'] ?? '';
    $city                 = $data['city'] ?? '';
    $province             = $data['province'] ?? '';
    $postal_code          = $data['postal_code'] ?? '';
    $country_code         = $data['country_code'] ?? 'ZA';
    $special_instructions = $data['special_instructions'] ?? null;
    $is_default           = isset($data['is_default']) ? (int)(bool)$data['is_default'] : 0;

    // If this address is being set as default, clear any existing default first
    if ($is_default) {
        $this->clearDefaultCollectionAddress($userId);
    }

    $sql = "INSERT INTO book_collection_addresses 
                (user_id, nickname, contact_name, contact_phone, contact_email,
                 unit_number, complex_name, street_number, street_name,
                 suburb, city, province, postal_code, country_code,
                 special_instructions, is_default)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "issssssssssssssi",
        $userId, $nickname, $contact_name, $contact_phone, $contact_email,
        $unit_number, $complex_name, $street_number, $street_name,
        $suburb, $city, $province, $postal_code, $country_code,
        $special_instructions, $is_default
    );

    if (!$stmt->execute()) return null;
    $newId = $stmt->insert_id;
    $stmt->close();
    return $newId;
}

public function updateCollectionAddress(int $addressId, int $userId, array $data): bool
{
    $this->createCollectionAddressTable();

    $nickname             = $data['nickname'] ?? '';
    $contact_name         = $data['contact_name'] ?? '';
    $contact_phone        = $data['contact_phone'] ?? '';
    $contact_email        = $data['contact_email'] ?? '';
    $unit_number          = $data['unit_number'] ?? null;
    $complex_name         = $data['complex_name'] ?? null;
    $street_number        = $data['street_number'] ?? '';
    $street_name          = $data['street_name'] ?? '';
    $suburb               = $data['suburb'] ?? '';
    $city                 = $data['city'] ?? '';
    $province             = $data['province'] ?? '';
    $postal_code          = $data['postal_code'] ?? '';
    $country_code         = $data['country_code'] ?? 'ZA';
    $special_instructions = $data['special_instructions'] ?? null;
    $is_default           = isset($data['is_default']) ? (int)(bool)$data['is_default'] : 0;

    if ($is_default) {
        $this->clearDefaultCollectionAddress($userId);
    }

    $sql = "UPDATE book_collection_addresses
            SET nickname=?, contact_name=?, contact_phone=?, contact_email=?,
                unit_number=?, complex_name=?, street_number=?, street_name=?,
                suburb=?, city=?, province=?, postal_code=?, country_code=?,
                special_instructions=?, is_default=?
            WHERE id=? AND user_id=?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssii",
        $nickname, $contact_name, $contact_phone, $contact_email,
        $unit_number, $complex_name, $street_number, $street_name,
        $suburb, $city, $province, $postal_code, $country_code,
        $special_instructions, $is_default,
        $addressId, $userId
    );

    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

public function getCollectionAddresses(int $userId): array
{
    $this->createCollectionAddressTable();
    $sql = "SELECT * FROM book_collection_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC";
    return $this->fetchPrepared($sql, "i", [$userId]);
}

public function getCollectionAddressById(int $addressId, int $userId): ?array
{
    $this->createCollectionAddressTable();
    $sql = "SELECT * FROM book_collection_addresses WHERE id = ? AND user_id = ?";
    $res = $this->fetchPrepared($sql, "ii", [$addressId, $userId]);
    return $res[0] ?? null;
}

public function getDefaultCollectionAddress(int $userId): ?array
{
    $this->createCollectionAddressTable();
    $sql = "SELECT * FROM book_collection_addresses WHERE user_id = ? AND is_default = 1 LIMIT 1";
    $res = $this->fetchPrepared($sql, "i", [$userId]);
    return $res[0] ?? null;
}

public function setDefaultCollectionAddress(int $addressId, int $userId): bool
{
    $this->createCollectionAddressTable();
    $this->clearDefaultCollectionAddress($userId);
    $sql = "UPDATE book_collection_addresses SET is_default = 1 WHERE id = ? AND user_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $addressId, $userId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

public function removeCollectionAddress(int $addressId, int $userId): bool
{
    $this->createCollectionAddressTable();
    $sql = "DELETE FROM book_collection_addresses WHERE id = ? AND user_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $addressId, $userId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

private function clearDefaultCollectionAddress(int $userId): void
{
    $sql = "UPDATE book_collection_addresses SET is_default = 0 WHERE user_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
}
}
