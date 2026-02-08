<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class DoctorProfile extends Model
{
    protected $table_name = "doctor_profiles";

    public function getAllDoctors()
    {
        // specific logic to get doctor with user info and specialty
        $query = "SELECT d.*, u.first_name, u.last_name, s.name as specialty_name 
                  FROM " . $this->table_name . " d
                  JOIN users u ON d.user_id = u.id
                  JOIN specialties s ON d.specialty_id = s.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDoctorsBySpecialty($specialtyId)
    {
        $query = "SELECT d.*, u.first_name, u.last_name, s.name as specialty_name 
                  FROM " . $this->table_name . " d
                  JOIN users u ON d.user_id = u.id
                  JOIN specialties s ON d.specialty_id = s.id
                  WHERE d.specialty_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $specialtyId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDoctorIdByUserId($userId)
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE user_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        }
        return false;
    }

    public function getDoctorById($id)
    {
        $query = "SELECT d.*, u.first_name, u.last_name, u.email, u.phone, s.name as specialty_name 
                  FROM " . $this->table_name . " d
                  JOIN users u ON d.user_id = u.id
                  JOIN specialties s ON d.specialty_id = s.id
                  WHERE d.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
