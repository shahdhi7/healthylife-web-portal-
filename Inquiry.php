<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Inquiry extends Model
{
    protected $table_name = "inquiries";

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $subject, $message, $userId = null)
    {
        $query = "INSERT INTO " . $this->table_name . " (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $name);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $subject);
        $stmt->bindParam(5, $message);
        return $stmt->execute();
    }

    public function reply($id, $response)
    {
        $query = "UPDATE " . $this->table_name . " SET response = ?, status = 'replied' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $response);
        $stmt->bindParam(2, $id);
        return $stmt->execute();
    }

    public function getByPatient($userId)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
