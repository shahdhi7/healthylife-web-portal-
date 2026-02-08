<?php

namespace App\Core;

use App\Config\Database;
use PDO;

class ActivityLogger
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function log($userId, $action, $details = null)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

        $sql = "INSERT INTO activity_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $action, $details, $ip]);
    }

    public function getLogs($limit = 50)
    {
        $sql = "SELECT l.*, u.first_name, u.last_name, u.role 
                FROM activity_logs l 
                LEFT JOIN users u ON l.user_id = u.id 
                ORDER BY l.created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
