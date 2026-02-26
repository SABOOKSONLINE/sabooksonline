<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Model
{
    protected mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    protected function createTable(string $tableName, array $columns): bool
    {
        $cols = [];

        foreach ($columns as $name => $definition) {
            // support numeric keys for raw SQL segments (indexes, constraints, etc.)
            if (is_int($name)) {
                // caller provided a full definition string like "UNIQUE KEY ..." or "KEY ..."
                $cols[] = $definition;
            } else {
                $cols[] = "`$name` $definition";
            }
        }

        $columnsSQL = implode(", ", $cols);
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` ($columnsSQL);";

        if ($this->conn->query($sql)) {
            return true;
        }
        throw new Exception("Table creation failed: " . $this->conn->error);
    }

    /** ---------------- FETCH METHODS ---------------- */

    protected function fetch(string $sql): array
    {
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Database query failed: " . $this->conn->error);
        }

        return $result->fetch_assoc() ?: [];
    }

    protected function fetchAll(string $sql): array
    {
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Database query failed: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC) ?: [];
    }

    protected function fetchPrepared(string $sql, string $types = '', array $params = []): array
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL prepare failed: " . $this->conn->error);
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }


    protected function insert(string $sql, string $types = '', array $params = []): int
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL prepare failed: " . $this->conn->error);
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Insert failed: " . $stmt->error);
        }

        $insertId = $stmt->insert_id;
        $stmt->close();
        return $insertId;
    }

    protected function update(string $sql, string $types = '', array $params = []): int
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL prepare failed: " . $this->conn->error);
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Update failed: " . $stmt->error);
        }

        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }

    protected function delete(string $sql, string $types = '', array $params = []): int
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL prepare failed: " . $this->conn->error);
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Delete failed: " . $stmt->error);
        }

        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }
}
