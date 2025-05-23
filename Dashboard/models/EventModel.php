<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class EventsModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function selectEventsByUserId($userId)
    {
        $sql = "SELECT * FROM events WHERE USERID = ? ORDER BY ID DESC";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $events = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $events;
    }

    public function selectEventByContentId($userId, $contentId)
    {
        $sql = "SELECT * FROM events WHERE USERID = ? AND CONTENTID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $userId, $contentId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return null;
        }

        $event = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $event;
    }

    public function insertEvent($data)
    {
        $data['contentid'] = uniqid('', true);

        $sql = "INSERT INTO events (
            USERID, COVER, TITLE, EMAIL, NUMBER, 
            VENUE, EVENTDATE, EVENTTIME, EVENTEND, TIMEEND, 
            DESCRIPTION, EVENTTYPE, ATTENDANCE, DURATION, LINK, CONTENTID, STATUS
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssssssss",
            $data['userid'],
            $data['cover'],
            $data['title'],
            $data['email'],
            $data['phone'],
            $data['venue'],
            $data['event_start_date'],
            $data['event_time'],
            $data['event_end_date'],
            $data['end_time'],
            $data['description'],
            $data['event_type'],
            $data['attendance'],
            $data['duration'],
            $data['link'],
            $data['contentid'],
            $data['status']
        );

        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            die("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        return $success;
    }

    public function updateEvent($id, $data)
    {
        $sql = "UPDATE events SET 
            USERID = ?,
            COVER = ?,
            TITLE = ?,
            EMAIL = ?,
            NUMBER = ?,
            VENUE = ?,
            EVENTDATE = ?,
            EVENTTIME = ?,
            EVENTEND = ?,
            TIMEEND = ?,
            DESCRIPTION = ?,
            EVENTTYPE = ?,
            ATTENDANCE = ?,
            DURATION = ?,
            LINK = ?,
            STATUS = ?
        WHERE ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssssi",
            $data['userid'],
            $data['cover'],
            $data['title'],
            $data['email'],
            $data['phone'],
            $data['venue'],
            $data['event_start_date'],
            $data['event_time'],
            $data['event_end_date'],
            $data['end_time'],
            $data['description'],
            $data['event_type'],
            $data['attendance'],
            $data['duration'],
            $data['link'],
            $data['status'],
            $id
        );

        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            die("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        return $success && $affectedRows > 0;
    }

    public function deleteEvent($id)
    {
        $sql = "DELETE FROM events WHERE ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        return $affectedRows > 0;
    }
}
