<?php

class UserModel {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    public function getUserByNameOrKey($contentid) {
        $contentid = str_replace('-', ' ', $contentid);
        $sql = "SELECT * FROM users WHERE ADMIN_NAME = ? OR ADMIN_USERKEY = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $contentid, $contentid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc(); // Only using first match
    }

    public function getPostsByUserKey($userKey) {
        $sql = "SELECT * FROM posts WHERE USERID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $userKey);
        $stmt->execute();
        $result = $stmt->get_result();

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    }
}
