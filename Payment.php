<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Payment extends Model
{
    protected $table_name = "payments";

    public function create($appointmentId, $amount, $method = 'credit_card')
    {
        $query = "INSERT INTO " . $this->table_name . " (appointment_id, amount, payment_method) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $appointmentId);
        $stmt->bindParam(2, $amount);
        $stmt->bindParam(3, $method);
        return $stmt->execute();
    }

    public function getByAppointment($appointmentId)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE appointment_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $appointmentId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByPatient($patientId)
    {
        $query = "SELECT p.*, a.appointment_date, a.time_slot, d.first_name as doc_fname, d.last_name as doc_lname 
                  FROM " . $this->table_name . " p
                  JOIN appointments a ON p.appointment_id = a.id
                  JOIN doctor_profiles dp ON a.doctor_id = dp.id
                  JOIN users d ON dp.user_id = d.id
                  WHERE a.patient_id = ?
                  ORDER BY p.payment_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $patientId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = ?, payment_date = CURRENT_TIMESTAMP 
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $id]);
    }
}
