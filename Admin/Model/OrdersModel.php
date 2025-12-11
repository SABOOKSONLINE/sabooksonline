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
                da.country
            FROM order_items oi
            LEFT JOIN posts b ON oi.book_id = b.ID
            LEFT JOIN orders o ON oi.order_id = o.id
            LEFT JOIN delivery_addresses da ON o.delivery_address_id = da.id
            WHERE oi.order_id = ?",
            "i",
            [$orderId]
        );
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
