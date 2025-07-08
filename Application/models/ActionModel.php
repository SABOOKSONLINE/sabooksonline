<?php
class ActionModel
{
    private $db;

    public function __construct($conn)
    {
        $this->db = $conn;
    }

    public function hasAction($userId, $bookId, $actionType)
    {
        $stmt = $this->db->prepare("SELECT id FROM user_book_actions WHERE user_id = ? AND book_id = ? AND action_type = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("sss", $userId, $bookId, $actionType);
        $stmt->execute();

        // Bind the result variable
        $stmt->bind_result($id);

        // Fetch returns true if there is a row, false otherwise
        $hasRow = $stmt->fetch();

        $stmt->close();

        return $hasRow;
    }

    public function recordAction($userId, $bookId, $actionType)
    {
        $stmt = $this->db->prepare("INSERT INTO user_book_actions (user_id, book_id, action_type) VALUES (?, ?, ?)");
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("sss", $userId, $bookId, $actionType);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function removeAction($userId, $bookId, $actionType)
    {
        $stmt = $this->db->prepare("DELETE FROM user_book_actions WHERE user_id = ? AND book_id = ? AND action_type = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("sss", $userId, $bookId, $actionType);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}
