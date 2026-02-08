<?php

require_once '../app/Config/Database.php';
require_once '../app/Core/ActivityLogger.php';

use App\Config\Database;
use App\Core\ActivityLogger;

$db = new Database();
$conn = $db->getConnection();
$logger = new ActivityLogger();

echo "Seeding detailed data...<br>";

// Password: password123
$passwordHash = password_hash('password123', PASSWORD_BCRYPT);

// Clear tables first (Optional, valid for dev)
$conn->exec("SET FOREIGN_KEY_CHECKS = 0;");
$conn->exec("TRUNCATE TABLE users;");
$conn->exec("TRUNCATE TABLE doctor_profiles;");
$conn->exec("TRUNCATE TABLE doctor_availability;");
$conn->exec("TRUNCATE TABLE staff;");
$conn->exec("TRUNCATE TABLE activity_logs;");
$conn->exec("SET FOREIGN_KEY_CHECKS = 1;");

// 1. Create Admin
try {
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role, gender, date_of_birth) VALUES ('System', 'Admin', 'admin@healthylife.lk', ?, 'admin', 'Male', '1990-01-01')");
    $stmt->execute([$passwordHash]);
    echo "Created Admin: admin@healthylife.lk<br>";
} catch (Exception $e) {
    echo "Admin Error: " . $e->getMessage() . "<br>";
}

// 2. Create Receptionist
try {
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role, gender, date_of_birth) VALUES ('Front', 'Desk', 'reception@healthylife.lk', ?, 'receptionist', 'Female', '1995-05-15')");
    $stmt->execute([$passwordHash]);
    echo "Created Receptionist: reception@healthylife.lk<br>";
} catch (Exception $e) {
    echo "Receptionist Error: " . $e->getMessage() . "<br>";
}

// 3. Create Patient
try {
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role, gender, date_of_birth, phone, address) VALUES ('John', 'Doe', 'patient@healthylife.lk', ?, 'patient', 'Male', '1985-08-20', '0771234567', '123 Hospital Rd, Colombo')");
    $stmt->execute([$passwordHash]);
    $patientId = $conn->lastInsertId();
    echo "Created Patient: patient@healthylife.lk<br>";

    // Log registration
    $logger->log($patientId, 'Register', 'New patient registration');
} catch (Exception $e) {
    echo "Patient Error: " . $e->getMessage() . "<br>";
}

// 4. Create Doctors (with Availability)
$doctors = [
    ['Dr. Aruna Perera', 'aruna@healthylife.lk', 'Male', 'Cardiology', 'Expert Cardiologist.', 15, '08:00:00', '12:00:00'], // 8am-12pm
    ['Dr. Nimali Silva', 'nimali@healthylife.lk', 'Female', 'Pediatrics', 'Child Specialist.', 10, '09:00:00', '14:00:00'],
    ['Dr. Kasun Raj', 'kasun@healthylife.lk', 'Male', 'Neurology', 'Brain Specialist.', 20, '13:00:00', '17:00:00']
];

foreach ($doctors as $doc) {
    try {
        $names = explode(' ', $doc[0]);
        $fname = $names[1];
        $lname = isset($names[2]) ? $names[2] : $names[1];

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role, gender, date_of_birth) VALUES (?, ?, ?, ?, 'doctor', ?, '1980-01-01')");
        $stmt->execute([$fname, $lname, $doc[1], $passwordHash, $doc[2]]);
        $userId = $conn->lastInsertId();

        // Get Specialty ID
        $specialtyStmt = $conn->prepare("SELECT id FROM specialties WHERE name = ?");
        $specialtyStmt->execute([$doc[3]]);
        $spec = $specialtyStmt->fetch(PDO::FETCH_ASSOC);
        $specId = $spec ? $spec['id'] : 1;

        // Profile
        $profileStmt = $conn->prepare("INSERT INTO doctor_profiles (user_id, specialty_id, bio, experience_years) VALUES (?, ?, ?, ?)");
        $profileStmt->execute([$userId, $specId, $doc[4], $doc[5]]);
        $profileId = $conn->lastInsertId();

        // Availability (Mon-Fri)
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day) {
            $availStmt = $conn->prepare("INSERT INTO doctor_availability (doctor_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
            $availStmt->execute([$profileId, $day, $doc[6], $doc[7]]);
        }

        echo "Created Doctor: " . $doc[0] . "<br>";
    } catch (Exception $e) {
        echo "Error creating " . $doc[0] . ": " . $e->getMessage() . "<br>";
    }
}

// 5. Create Staff (for Admin Table)
try {
    $conn->exec("INSERT INTO staff (name, role, email, phone, joined_date) VALUES 
        ('Saman Kumara', 'Nurse', 'saman@hl.lk', '0712345678', '2020-01-01'),
        ('Kamala Perera', 'Janitor', 'kamala@hl.lk', '0723456789', '2019-05-20'),
        ('Ravi De Silva', 'Lab Tech', 'ravi@hl.lk', '0779876543', '2021-03-10')");
    echo "Created Staff Data<br>";
} catch (Exception $e) {
    echo "Staff Error: " . $e->getMessage() . "<br>";
}

echo "Seeding Completed!";
