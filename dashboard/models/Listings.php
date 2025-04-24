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
}
