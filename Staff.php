<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Staff extends Model
{
    protected $table_name = "staff";

    public function getAll($search = '')
    {
        if ($search) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE name LIKE ? OR role LIKE ? OR department LIKE ? OR phone LIKE ? OR email LIKE ? ORDER BY name ASC";
            $term = "%$search%";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $term);
            $stmt->bindParam(2, $term);
            $stmt->bindParam(3, $term);
            $stmt->bindParam(4, $term);
            $stmt->bindParam(5, $term);
        } else {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, role, department, description, email, phone, joined_date, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['name'],
            $data['role'],
            $data['department'],
            $data['description'],
            $data['email'],
            $data['phone'],
            $data['joined_date'],
            $data['image_path']
        ]);
    }
}
