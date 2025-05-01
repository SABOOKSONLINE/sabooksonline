<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PageVisitsModel
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getVisiters()
    {
        $sql = "SELECT * FROM 'page_visits' ORDER BY 'visit_time' DESC";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $visiters = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $visiters[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $visiters;
    }

    public function insertVisit($data)
    {
        $sql = "INSERT INTO page_visits (
            user_agent, 
            page_url, 
            referer, 
            visit_time, 
            duration, 
            user_country, 
            user_city, 
            user_province, 
            user_ip
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['user_agent'],
            $data['page_url'],
            $data['referer'],
            $data['visit_time'],
            $data['duration'],
            $data['user_country'],
            $data['user_city'],
            $data['user_province'],
            $data['user_ip']
        ]);
    }

    public function getVisitersByCountry($country)
    {
        $sql = "SELECT * FROM 'page_visits' WHERE user_country = ? ORDER BY 'visit_time' DESC";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_param($stmt, "s", $country);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $visiters = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $visiters[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $visiters;
    }

    public function getVisitersByProvince($province)
    {
        $sql = "SELECT * FROM 'page_visits' WHERE user_province = ? ORDER BY 'visit_time' DESC";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_param($stmt, "s", $province);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $visiters = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $visiters[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $visiters;
    }

    public function getVisitersByCity($city)
    {
        $sql = "SELECT * FROM 'page_visits' WHERE user_city = ? ORDER BY 'visit_time' DESC";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_param($stmt, "s", $city);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $visiters = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $visiters[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $visiters;
    }
}
