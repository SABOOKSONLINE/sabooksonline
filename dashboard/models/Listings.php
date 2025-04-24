<?php
class Listing {
  private $conn;

  public function __construct($dbConnection) {
    $this->conn = $dbConnection;
  }

  public function getUserBooks($userId) {
    $sql = "SELECT * FROM posts WHERE USERID = ? ORDER BY ID DESC;";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    return $stmt->get_result();
  }

  public function getUserEvents($userKey) {
        $sql = "SELECT * FROM events WHERE USERID = '$userKey' ORDER BY ID DESC";
        $result = mysqli_query($this->conn, $sql);
        $events = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $events[] = $row;
            }
        }

        return $events;
    }

    public function getUserServices($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM services WHERE USERID = ? ORDER BY ID DESC");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getUserReviews($userKey) {
        $stmt = $this->conn->prepare("
            SELECT r.*
            FROM reviews r
            JOIN posts p ON r.CONTENTID = p.CONTENTID
            WHERE p.USERID = ?
            ORDER BY r.ID DESC
        ");
        $stmt->bind_param("s", $userKey);
        $stmt->execute();
        $result = $stmt->get_result();

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        return $reviews;
    }

     public function getUserCustomers() {
        $sql = "SELECT * FROM user_info ORDER BY user_id DESC";
        $result = $this->conn->query($sql);

        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }

    // for user orders
    public function getNetIncome() {
        $result = mysqli_query($this->conn, "SELECT SUM(product_total) AS value_sum FROM product_order WHERE product_current = 'COMPLETED'");
        $row = mysqli_fetch_assoc($result);
        return $row['value_sum'] ?? 0;
    }

    public function getTransactionsCount() {
        $result = mysqli_query($this->conn, "SELECT COUNT(*) as number_rows FROM product_order WHERE product_current = 'COMPLETED'");
        $row = mysqli_fetch_assoc($result);
        return $row['number_rows'] ?? 0;
    }

    public function getTotalCustomers() {
        $result = mysqli_query($this->conn, "SELECT COUNT(*) as number_rows FROM user_info");
        $row = mysqli_fetch_assoc($result);
        return $row['number_rows'] ?? 0;
    }

    public function getPendingOrders() {
        $result = mysqli_query($this->conn, "SELECT COUNT(*) as number_rows FROM product_order WHERE product_current = 'cart'");
        $row = mysqli_fetch_assoc($result);
        return $row['number_rows'] ?? 0;
    }

    public function getAllInvoices() {
        $invoices = [];
        $result = mysqli_query($this->conn, "SELECT * FROM invoices ORDER BY invoice_id DESC");
        while ($row = mysqli_fetch_assoc($result)) {
            $userId = $row['invoice_user'];
            $userQuery = mysqli_query($this->conn, "SELECT * FROM user_info WHERE user_id = '$userId'");
            $user = mysqli_fetch_assoc($userQuery);

            $status = $row['invoice_status'] === 'COMPLETED' 
                ? '<span class="pending-style style2">Completed</span>' 
                : '<span class="pending-style style1">Pending</span>';

            $invoices[] = [
                'number' => $row['invoice_number'],
                'date' => $row['invoice_date'],
                'total' => $row['invoice_total'],
                'status' => $status,
                'user_fullname' => isset($user) ? ucwords($user['first_name'].' '.$user['last_name']) : 'N/A',
                'user_id' => $userId
            ];
        }
        return $invoices;
    }
}
