<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Appointment extends Model
{
    protected $table_name = "appointments";

    public function getSlotCounts($doctorId, $date)
    {
        $query = "SELECT time_slot, COUNT(*) as booked_count 
                  FROM " . $this->table_name . " 
                  WHERE doctor_id = ? AND appointment_date = ? AND status NOT LIKE 'cancelled%'
                  GROUP BY time_slot";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $doctorId);
        $stmt->bindParam(2, $date);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function create($patientId, $doctorId, $date, $timeSlot)
    {
        // Enforce 5 slots per hour logic constraint?
        // Actually, the requirement says "5 appointments per hour".
        // If the Frontend generates slots like 09:00, 09:12, 09:24... AND we ensure unique constraints on (doc, date, time),
        // then we don't need to count *total* per hour here unless we are allowing overlapping times.
        // User requirment: "each hour is split into 5 slots only".
        // SO, if the slots are PRE-DEFINED, we just need to check if the specific slot is taken.
        // The UNIQUE constraint in DB handles the specific slot double-booking.

        $query = "INSERT INTO " . $this->table_name . " 
                  (patient_id, doctor_id, appointment_date, time_slot, status) 
                  VALUES (?, ?, ?, ?, 'pending')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $patientId);
        $stmt->bindParam(2, $doctorId);
        $stmt->bindParam(3, $date);
        $stmt->bindParam(4, $timeSlot);

        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false; // Likely constraint violation (double book)
        }
    }

    public function getByPatient($patientId)
    {
        $query = "SELECT a.*, d.first_name as doc_fname, d.last_name as doc_lname, s.name as specialty, a.room_number 
                  FROM " . $this->table_name . " a
                  JOIN doctor_profiles dp ON a.doctor_id = dp.id
                  JOIN users d ON dp.user_id = d.id
                  JOIN specialties s ON dp.specialty_id = s.id
                  WHERE a.patient_id = ?
                  ORDER BY a.appointment_date DESC, a.time_slot DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $patientId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByDoctor($doctorId, $date = null, $history = false)
    {
        $query = "SELECT a.*, u.first_name, u.last_name, u.email, u.gender, u.phone, u.date_of_birth, a.room_number 
                  FROM " . $this->table_name . " a
                  JOIN users u ON a.patient_id = u.id
                  WHERE a.doctor_id = ?";

        if ($date) {
            $query .= " AND a.appointment_date = ?";
        } elseif ($history) {
            $query .= " AND a.appointment_date < CURDATE()";
        }

        $query .= " ORDER BY a.appointment_date DESC, a.time_slot ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $doctorId);
        if ($date) {
            $stmt->bindParam(2, $date);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUpcoming()
    {
        $date = date('Y-m-d');
        $query = "SELECT a.*, p.first_name as p_fname, p.last_name as p_lname, 
                         d.first_name as d_fname, d.last_name as d_lname, s.name as specialty
                  FROM " . $this->table_name . " a
                  JOIN users p ON a.patient_id = p.id
                  JOIN doctor_profiles dp ON a.doctor_id = dp.id
                  JOIN users d ON dp.user_id = d.id
                  JOIN specialties s ON dp.specialty_id = s.id
                  WHERE a.appointment_date >= ?
                  ORDER BY a.appointment_date ASC, a.time_slot ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRoom($id, $room)
    {
        $query = "UPDATE " . $this->table_name . " SET room_number = ?, status = 'confirmed' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $room);
        $stmt->bindParam(2, $id);
        return $stmt->execute();
    }

    public function getCount()
    {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM " . $this->table_name);
        return $stmt->fetchColumn();
    }

    public function updateStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_name . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $id);
        return $stmt->execute();
    }
    public function getAll()
    {
        $query = "SELECT a.*, p.first_name as p_fname, p.last_name as p_lname, 
                         d.first_name as d_fname, d.last_name as d_lname, s.name as specialty
                  FROM " . $this->table_name . " a
                  JOIN users p ON a.patient_id = p.id
                  JOIN doctor_profiles dp ON a.doctor_id = dp.id
                  JOIN users d ON dp.user_id = d.id
                  JOIN specialties s ON dp.specialty_id = s.id
                  ORDER BY a.appointment_date DESC, a.time_slot DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
