<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class DoctorAvailability extends Model
{
    protected $table_name = "doctor_availability";

    public function getByDoctor($doctorId)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE doctor_id = ? ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $doctorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setAvailability($doctorId, $day, $startTime, $endTime, $isAvailable = true)
    {
        // Check if exists
        $query = "SELECT id FROM " . $this->table_name . " WHERE doctor_id = ? AND day_of_week = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $doctorId);
        $stmt->bindParam(2, $day);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Update
            $query = "UPDATE " . $this->table_name . " SET start_time = ?, end_time = ?, is_available = ? WHERE doctor_id = ? AND day_of_week = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $startTime);
            $stmt->bindParam(2, $endTime);
            $stmt->bindParam(3, $isAvailable);
            $stmt->bindParam(4, $doctorId);
            $stmt->bindParam(5, $day);
        } else {
            // Insert
            $query = "INSERT INTO " . $this->table_name . " (doctor_id, day_of_week, start_time, end_time, is_available) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $doctorId);
            $stmt->bindParam(2, $day);
            $stmt->bindParam(3, $startTime);
            $stmt->bindParam(4, $endTime);
            $stmt->bindParam(5, $isAvailable);
        }
        return $stmt->execute();
    }
}
