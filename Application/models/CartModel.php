<?php

require_once __DIR__ . "/../Core/Model.php";

class CartModel extends Model
{
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
            "book_id"     => "INT NOT NULL",
            "quantity"    => "INT NOT NULL DEFAULT 1",
            "unit_price"  => "DECIMAL(10,2) NOT NULL",
            "total_price" => "DECIMAL(10,2) NOT NULL",
            "created_at"  => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
        ];
        return $this->createTable("order_items", $columns);
    }

    public function getCartItemsByUserId(int $userId): array
    {
        $this->createCartTable();
        $sql = "SELECT bc.*, hc.*, b.COVER AS cover_image, b.TITLE AS title, b.DESCRIPTION AS description,
                       b.ISBN AS isbn, b.CATEGORY AS category, b.LANGUAGES AS language,
                       b.CONTENTID AS book_public_key, b.PUBLISHER AS publisher_name,
                       b.DATEPOSTED AS date, b.AUTHORS AS authors
                FROM book_cart AS bc
                JOIN posts AS b ON bc.book_id = b.ID
                LEFT JOIN book_hardcopy AS hc ON hc.book_id = b.ID
                WHERE bc.user_id = ?
                ORDER BY bc.created_at DESC";
        return $this->fetchPrepared($sql, "i", [$userId]);
    }

    public function addItem(int $userId, int $bookId, int $qty = 1): bool
    {
        $this->createCartTable();
        $sqlCheck = "SELECT cart_item_count FROM book_cart WHERE user_id = ? AND book_id = ?";
        $existing = $this->fetchPrepared($sqlCheck, "ii", [$userId, $bookId]);
        if (!empty($existing)) {
            $sql = "UPDATE book_cart SET cart_item_count = ? WHERE user_id = ? AND book_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $qty, $userId, $bookId);
            $stmt->execute();
            $stmt->close();
            return true;
        }
        $sql = "INSERT INTO book_cart (user_id, book_id, cart_item_count) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $bookId, $qty);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function updateItemCount(int $userId, int $bookId, int $qty): bool
    {
        $this->createCartTable();
        if ($qty <= 0) return $this->removeItem($userId, $bookId);
        $sql = "UPDATE book_cart SET cart_item_count = ? WHERE user_id = ? AND book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $qty, $userId, $bookId);
        return $stmt->execute();
    }

    public function removeItem(int $userId, int $bookId): bool
    {
        $this->createCartTable();
        $sql = "DELETE FROM book_cart WHERE user_id = ? AND book_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $bookId);
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
        foreach ($cartItems as $item) $subtotal += ($item["PRICE"] ?? 0) * $item["cart_item_count"];
        $shippingFee = 60;
        $total = $subtotal + $shippingFee;
        $sql = "INSERT INTO orders (user_id, delivery_address_id, order_number, total_amount, shipping_fee) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisdd", $userId, $address["id"], $orderNumber, $total, $shippingFee);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();
        foreach ($cartItems as $item) {
            $price = $item["PRICE"] ?? 0;
            $qty = $item["cart_item_count"];
            $totalPrice = $price * $qty;
            $sqlItem = "INSERT INTO order_items (order_id, book_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)";
            $stmtItem = $this->conn->prepare($sqlItem);
            $stmtItem->bind_param("iiidd", $orderId, $item["book_id"], $qty, $price, $totalPrice);
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
        $items = $this->fetchPrepared("SELECT * FROM order_items WHERE order_id=?", "i", [$orderId]);
        return ["order" => $order[0], "items" => $items];
    }
}
