<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Automatically detect environment
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            // Localhost settings
            $this->host = "localhost";
            $this->db_name = "healthylife_hospital";
            $this->username = "root";
            $this->password = "";
        } else {
            // InfinityFree settings
            $this->host = "sql106.infinityfree.com";       // Replace with your InfinityFree host
            $this->db_name = "if0_41082325_healthylife";           // Replace with your InfinityFree DB name
            $this->username = "if0_41082325";            // Replace with your InfinityFree username
            $this->password = "X0k2WMsHOV";             // Replace with your InfinityFree password
        }
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
