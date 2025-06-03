<?php
// filepath: models/Encuesta.php
require_once 'Database.php';

class Encuesta
{
    private $conn;
    private $table_name = "web";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function obtenerTodas()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
