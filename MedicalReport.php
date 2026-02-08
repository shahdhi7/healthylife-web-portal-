<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class MedicalReport extends Model
{
    protected $table_name = "medical_reports";

    public function getByPatient($patientId)
    {
        $query = "SELECT m.*, d.first_name as doc_fname, d.last_name as doc_lname 
                  FROM " . $this->table_name . " m
                  JOIN doctor_profiles dp ON m.doctor_id = dp.id
                  JOIN users d ON dp.user_id = d.id
                  WHERE m.patient_id = ?
                  ORDER BY m.uploaded_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $patientId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($patientId, $doctorId, $filePath, $fileName, $description)
    {
        $query = "INSERT INTO " . $this->table_name . " (patient_id, doctor_id, file_path, file_name, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $patientId);
        $stmt->bindParam(2, $doctorId);
        $stmt->bindParam(3, $filePath);
        $stmt->bindParam(4, $fileName);
        $stmt->bindParam(5, $description);
        return $stmt->execute();
    }
}
