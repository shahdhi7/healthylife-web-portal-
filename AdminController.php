<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Config\Database;

class AdminController extends Controller
{
    private $db;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            // Ideally redirect, but constructor can't return. 
            // We'll check in methods or use a middleware approach if we had one.
        }
        $this->db = (new Database())->getConnection();
    }

    // --- USERS MANAGEMENT ---
    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $this->redirect('dashboard/users');
        }
    }

    public function editUserForm()
    {
        $id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->view('admin/edit_user', ['user' => $user]);
    }

    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $gender = $_POST['gender'];
            $dob = $_POST['dob']; // Maps to date_of_birth in DB

            // Handle Password Update if provided
            if (!empty($_POST['password'])) {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("UPDATE users SET first_name=?, last_name=?, email=?, role=?, gender=?, date_of_birth=?, password_hash=? WHERE id=?");
                $stmt->execute([$firstName, $lastName, $email, $role, $gender, $dob, $passwordHash, $id]);
            } else {
                $stmt = $this->db->prepare("UPDATE users SET first_name=?, last_name=?, email=?, role=?, gender=?, date_of_birth=? WHERE id=?");
                $stmt->execute([$firstName, $lastName, $email, $role, $gender, $dob, $id]);
            }

            $this->redirect('dashboard/users');
        }
    }

    public function addUserForm()
    {
        $this->view('admin/add_user');
    }

    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'] ?? 'patient';
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];

            $sql = "INSERT INTO users (first_name, last_name, email, password_hash, role, gender, date_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            try {
                if ($stmt->execute([$firstName, $lastName, $email, $password, $role, $gender, $dob])) {
                    $this->redirect('dashboard/users');
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    // Duplicate entry
                    $this->view('admin/add_user', ['error' => 'Email address already exists.']);
                } else {
                    die("Error creating user: " . $e->getMessage());
                }
            }
        }
    }

    // --- APPOINTMENTS MANAGEMENT ---
    public function appointments()
    {
        // Full list with joins
        $sql = "SELECT a.*, 
                       p.first_name as p_fname, p.last_name as p_lname, p.email as p_email,
                       d.first_name as d_fname, d.last_name as d_lname,
                       s.name as specialty
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN doctor_profiles dp ON a.doctor_id = dp.id
                JOIN users d ON dp.user_id = d.id
                JOIN specialties s ON dp.specialty_id = s.id
                ORDER BY a.appointment_date DESC, a.time_slot DESC";
        $stmt = $this->db->query($sql);
        $appointments = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->view('dashboard/admin_appointments', ['appointments' => $appointments]);
    }

    public function cancelAppointment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $appointmentModel = $this->model('Appointment');
            $appointmentModel->updateStatus($id, 'cancelled');
            $this->redirect('admin/appointments');
        }
    }

    // --- STAFF MANAGEMENT ---
    public function deleteStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $stmt = $this->db->prepare("DELETE FROM staff WHERE id = ?");
            $stmt->execute([$id]);
            $this->redirect('dashboard/staff');
        }
    }

    public function addStaffForm()
    {
        $this->view('admin/add_staff');
    }

    public function createStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'role' => $_POST['role'],
                'department' => $_POST['department'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'description' => $_POST['description'],
                'joined_date' => date('Y-m-d'),
                'image_path' => 'assets/images/staff/default.png'
            ];

            $staffModel = $this->model('Staff');
            if ($staffModel->create($data)) {
                $this->redirect('dashboard/staff');
            } else {
                die("Error creating staff record.");
            }
        }
    }

    public function editStaffForm()
    {
        $id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM staff WHERE id = ?");
        $stmt->execute([$id]);
        $staffMember = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->view('admin/edit_staff', ['staff' => $staffMember]);
    }

    public function updateStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $role = $_POST['role'];
            $department = $_POST['department'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $description = $_POST['description'];

            $stmt = $this->db->prepare("UPDATE staff SET name=?, role=?, department=?, email=?, phone=?, description=? WHERE id=?");
            if ($stmt->execute([$name, $role, $department, $email, $phone, $description, $id])) {
                $this->redirect('dashboard/staff');
            } else {
                die("Error updating staff record.");
            }
        }
    }

    // --- SPECIALTIES MANAGEMENT ---
    public function specialties()
    {
        $stmt = $this->db->query("SELECT * FROM specialties");
        $specialties = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->view('dashboard/admin_specialties', ['specialties' => $specialties]);
    }

    public function addSpecialty()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $stmt = $this->db->prepare("INSERT IGNORE INTO specialties (name) VALUES (?)");
            $stmt->execute([$name]);
            $this->redirect('admin/specialties');
        }
    }

    public function deleteSpecialty()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $stmt = $this->db->prepare("DELETE FROM specialties WHERE id = ?");
            $stmt->execute([$id]);
            $this->redirect('admin/specialties');
        }
    }

    public function editDoctorForm()
    {
        $id = $_GET['id']; // doctor_profile id
        $sql = "SELECT d.*, u.first_name, u.last_name, u.email, u.gender, u.date_of_birth
                FROM doctor_profiles d
                JOIN users u ON d.user_id = u.id
                WHERE d.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $doctor = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT * FROM specialties");
        $specialties = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view('admin/edit_doctor', [
            'doctor' => $doctor,
            'specialties' => $specialties
        ]);
    }

    public function updateDoctor()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $doctorId = $_POST['doctor_id'];
            $userId = $_POST['user_id'];
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];
            $specialtyId = $_POST['specialty_id'];
            $experience = $_POST['experience'];
            $bio = $_POST['bio'];

            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileExtension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = 'doctor_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
                        $imagePath = $fileName;
                    }
                }
            }

            $this->db->beginTransaction();
            try {
                // Update User
                $sqlUser = "UPDATE users SET first_name=?, last_name=?, email=?, gender=?, date_of_birth=? WHERE id=?";
                $stmtUser = $this->db->prepare($sqlUser);
                $stmtUser->execute([$firstName, $lastName, $email, $gender, $dob, $userId]);

                // Update Doctor Profile
                if ($imagePath) {
                    $sqlDoc = "UPDATE doctor_profiles SET specialty_id=?, experience_years=?, bio=?, image_path=? WHERE id=?";
                    $stmtDoc = $this->db->prepare($sqlDoc);
                    $stmtDoc->execute([$specialtyId, $experience, $bio, $imagePath, $doctorId]);
                } else {
                    $sqlDoc = "UPDATE doctor_profiles SET specialty_id=?, experience_years=?, bio=? WHERE id=?";
                    $stmtDoc = $this->db->prepare($sqlDoc);
                    $stmtDoc->execute([$specialtyId, $experience, $bio, $doctorId]);
                }

                $this->db->commit();
                $this->redirect('dashboard/doctors');
            } catch (\Exception $e) {
                $this->db->rollBack();
                die("Error updating doctor: " . $e->getMessage());
            }
        }
    }

    public function addDoctorForm()
    {
        $stmt = $this->db->query("SELECT * FROM specialties");
        $specialties = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->view('admin/add_doctor', ['specialties' => $specialties]);
    }

    public function createDoctor()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];
            $specialtyId = $_POST['specialty_id'];
            $experience = $_POST['experience'];
            $bio = $_POST['bio'];

            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileExtension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = 'doctor_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
                        $imagePath = $fileName;
                    }
                }
            }

            $this->db->beginTransaction();
            try {
                // 1. Create User
                $sqlUser = "INSERT INTO users (first_name, last_name, email, password_hash, role, gender, date_of_birth) VALUES (?, ?, ?, ?, 'doctor', ?, ?)";
                $stmtUser = $this->db->prepare($sqlUser);
                $stmtUser->execute([$firstName, $lastName, $email, $password, $gender, $dob]);
                $userId = $this->db->lastInsertId();

                // 2. Create Doctor Profile with image
                $sqlDoc = "INSERT INTO doctor_profiles (user_id, specialty_id, experience_years, bio, image_path) VALUES (?, ?, ?, ?, ?)";
                $stmtDoc = $this->db->prepare($sqlDoc);
                $stmtDoc->execute([$userId, $specialtyId, $experience, $bio, $imagePath]);

                $this->db->commit();
                $this->redirect('dashboard/doctors');
            } catch (\Exception $e) {
                $this->db->rollBack();
                die("Error creating doctor: " . $e->getMessage());
            }
        }
    }
}
