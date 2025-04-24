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

  public function fetchUserEvents($userKey) {
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
}
