<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class OrdersModel extends Model
{
    private function tableExists(string $table): bool
    {
        $sql = "SELECT COUNT(*)
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
                AND table_name = ?";

        $res = $this->fetchPrepared($sql, "s", [$table]);

        if (empty($res)) {
            return false;
        }

        $count = array_values($res)[0];

        return $count > 0;
    }

    public function createOrder(int $userId): int
    {
        if (!$this->tableExists("orders")) {
            return 0;
        }

        return $this->insert(
            "INSERT INTO orders (user_id) VALUES (?)",
            "i",
            [$userId]
        );
    }


    public function updateOrderTotals(int $orderId, float $grandTotal, float $shippingFee, string $paymentMethod): int
    {
        if (!$this->tableExists("orders")) {
            return 0;
        }

        $sql = "UPDATE orders 
                SET total_amount = ?, shipping_fee = ?, payment_method = ?
                WHERE id = ?";

        return $this->update(
            $sql,
            "ddsi",
            [$grandTotal, $shippingFee, $paymentMethod, $orderId]
        );
    }


    public function addOrderItem(int $orderId, array $item): int
    {
        if (!$this->tableExists("order_items")) {
            return 0;
        }

        $sql = "INSERT INTO order_items 
                (order_id, book_id, quantity, unit_price, total_price)
                VALUES (?, ?, ?, ?, ?)";

        return $this->insert(
            $sql,
            "iiidd",
            [
                $orderId,
                $item["id"],
                $item["cart_item_count"],
                $item["hc_price"],
                $item["hc_price"] * $item["cart_item_count"]
            ]
        );
    }

    public function getOrder(int $orderId): array
    {
        if (!$this->tableExists("orders")) {
            return [];
        }

        return $this->fetchPrepared(
            "SELECT * FROM orders WHERE id = ?",
            "i",
            [$orderId]
        );
    }


    public function getOrderItems(int $orderId): array
    {
        if (!$this->tableExists("order_items")) {
            return [];
        }

        // Ensure book_type column exists (migration)
        $this->ensureBookTypeColumn();

        // Check if book_type column exists
        $hasBookType = $this->columnExists("order_items", "book_type");

        // Build query based on whether book_type exists
        if ($hasBookType) {
            return $this->fetchPrepared(
                "SELECT 
                    oi.*, 
                    COALESCE(b.TITLE, ab.title) AS TITLE,
                    COALESCE(b.AUTHORS, ab.author) AS AUTHORS,
                    da.full_name,
                    da.company,
                    da.phone,
                    da.email,
                    da.street_address,
                    da.street_address2,
                    da.delivery_type,
                    da.local_area,
                    da.zone,
                    da.postal_code,
                    da.country,
                    COALESCE(oi.book_type, 'regular') AS book_type
                FROM order_items oi
                LEFT JOIN posts b ON oi.book_id = CAST(b.ID AS CHAR) AND (oi.book_type = 'regular' OR oi.book_type IS NULL)
                LEFT JOIN academic_books ab ON oi.book_id = ab.public_key AND oi.book_type = 'academic'
                LEFT JOIN orders o ON oi.order_id = o.id
                LEFT JOIN delivery_addresses da ON o.delivery_address_id = da.id
                WHERE oi.order_id = ?",
                "i",
                [$orderId]
            );
        } else {
            // Fallback for tables without book_type column (assume all are regular books)
            return $this->fetchPrepared(
                "SELECT 
                    oi.*, 
                    b.TITLE,
                    b.AUTHORS,
                    da.full_name,
                    da.company,
                    da.phone,
                    da.email,
                    da.street_address,
                    da.street_address2,
                    da.delivery_type,
                    da.local_area,
                    da.zone,
                    da.postal_code,
                    da.country,
                    'regular' AS book_type
                FROM order_items oi
                LEFT JOIN posts b ON oi.book_id = CAST(b.ID AS CHAR)
                LEFT JOIN orders o ON oi.order_id = o.id
                LEFT JOIN delivery_addresses da ON o.delivery_address_id = da.id
                WHERE oi.order_id = ?",
                "i",
                [$orderId]
            );
        }
    }

    private function columnExists(string $table, string $column): bool
    {
        try {
            $result = $this->conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
            return ($result && $result->num_rows > 0);
        } catch (Exception $e) {
            return false;
        }
    }

    private function ensureBookTypeColumn(): void
    {
        if (!$this->columnExists("order_items", "book_type")) {
            try {
                $this->conn->query("ALTER TABLE order_items ADD COLUMN book_type ENUM('regular', 'academic') DEFAULT 'regular' AFTER book_id");
            } catch (Exception $e) {
                error_log("Failed to add book_type column to order_items: " . $e->getMessage());
            }
        }
    }


    public function getAllOrders(): array
    {
        if (!$this->tableExists("orders")) {
            return [];
        }

        return $this->fetchAll("SELECT * FROM orders ORDER BY created_at DESC");
    }

    public function updateOrderStatus(int $orderId, string $status): int
    {
        if (!$this->tableExists("orders")) {
            return 0;
        }

        $sql = "UPDATE orders SET order_status = ?, updated_at = NOW() WHERE id = ?";
        return $this->update($sql, "si", [$status, $orderId]);
    }
}
