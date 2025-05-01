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

    public function getVisitors()
    {
        $sql = "SELECT * FROM page_visits ORDER BY visit_time DESC";

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

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssss",
            $data['user_agent'],
            $data['page_url'],
            $data['referer'],
            $data['visit_time'],
            $data['duration'],
            $data['user_country'],
            $data['user_city'],
            $data['user_province'],
            $data['user_ip']
        );

        return mysqli_stmt_execute($stmt);
    }

    public function updateDuration($pageUrl, $duration)
    {
        $sql = "UPDATE page_visits 
                SET duration = ? 
                WHERE page_url = ? 
                ORDER BY visit_time DESC 
                LIMIT 1";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $duration, $pageUrl);
        return mysqli_stmt_execute($stmt);
    }

    public function getVisitorsByCountry($country)
    {
        $sql = "SELECT * FROM page_visits WHERE user_country = ? ORDER BY visit_time DESC";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $country);
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

    public function getVisitorsByProvince($province)
    {
        $sql = "SELECT * FROM page_visits WHERE user_province = ? ORDER BY visit_time DESC";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $province);
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

    public function getVisitorsByCity($city)
    {
        $sql = "SELECT * FROM page_visits WHERE user_city = ? ORDER BY visit_time DESC";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $city);
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
}
