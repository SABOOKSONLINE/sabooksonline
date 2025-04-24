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
}
