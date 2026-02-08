<?php

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $role = $_SESSION['user_role'];

        // Dispatch to specific handler based on role
        if ($role === 'patient') {
            $this->patientDashboard();
        } elseif ($role === 'doctor') {
            $this->doctorDashboard();
        } elseif ($role === 'admin') {
            $this->adminDashboard();
        } elseif ($role === 'receptionist') {
            $this->receptionistDashboard();
        } else {
            echo "Unknown role";
        }
    }

    private function patientDashboard()
    {
        $view = $_GET['view'] ?? 'appointments';
        $userId = $_SESSION['user_id'];
        $data = ['view' => $view];

        if ($view === 'appointments') {
            $appointmentModel = $this->model('Appointment');
            $data['appointments'] = $appointmentModel->getByPatient($userId);
        } elseif ($view === 'reports') {
            $reportModel = $this->model('MedicalReport');
            $data['reports'] = $reportModel->getByPatient($userId);
        } elseif ($view === 'payments') {
            $paymentModel = $this->model('Payment');
            $data['payments'] = $paymentModel->getByPatient($userId);
        } elseif ($view === 'profile') {
            $userModel = $this->model('User');
            $data['user'] = $userModel->getById($userId);
        } elseif ($view === 'inquiries') {
            $inquiryModel = $this->model('Inquiry');
            $data['feedbacks'] = $inquiryModel->getByPatient($userId);
        }

        $this->view('dashboard/patient', $data);
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $userModel = $this->model('User');

            $updateData = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'gender' => $_POST['gender'],
                'date_of_birth' => $_POST['date_of_birth'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];

            if ($userModel->update($userId, $updateData)) {
                $_SESSION['user_name'] = $updateData['first_name'] . ' ' . $updateData['last_name'];
                $this->redirect('dashboard?view=profile&success=1');
            } else {
                $this->redirect('dashboard?view=profile&error=1');
            }
        }
    }

    private function doctorDashboard()
    {
        $view = $_GET['view'] ?? 'schedule';
        $userId = $_SESSION['user_id'];
        $doctorModel = $this->model('DoctorProfile');
        $doctorId = $doctorModel->getDoctorIdByUserId($userId);

        if (!$doctorId) {
            echo "Error: Doctor profile not found.";
            return;
        }

        $data = ['view' => $view, 'doctor_id' => $doctorId];

        if ($view === 'schedule') {
            $appointmentModel = $this->model('Appointment');
            $date = $_GET['date'] ?? date('Y-m-d');
            $data['date'] = $date;
            // Fetch appointments for this doctor and date with patient details
            $data['appointments'] = $appointmentModel->getByDoctor($doctorId, $date);
        } elseif ($view === 'history') {
            $appointmentModel = $this->model('Appointment');
            $data['appointments'] = $appointmentModel->getByDoctor($doctorId, null, true);
        } elseif ($view === 'profile') {
            $userModel = $this->model('User');
            $data['user'] = $userModel->getById($userId);
        }

        $this->view('dashboard/doctor', $data);
    }

    private function adminDashboard()
    {
        // Admin Dashboard Main View
        $userModel = $this->model('User');
        $doctorModel = $this->model('DoctorProfile');
        $appointmentModel = $this->model('Appointment');
        $logger = new \App\Core\ActivityLogger();

        $data = [
            'total_patients' => $userModel->getCountByRole('patient'),
            'total_doctors' => $userModel->getCountByRole('doctor'),
            'total_appointments' => $appointmentModel->getCount(),
            'recent_logs' => $logger->getLogs(10),
        ];

        $this->view('dashboard/admin', $data);
    }

    private function receptionistDashboard()
    {
        $view = $_GET['view'] ?? 'billing';
        $data = ['view' => $view];

        $db = (new \App\Config\Database())->getConnection();

        if ($view === 'billing') {
            // Get all appointments with patient and doctor info for billing
            $sql = "SELECT a.*, 
                    p_user.first_name as patient_first, p_user.last_name as patient_last,
                    d_user.first_name as doctor_first, d_user.last_name as doctor_last,
                    s.name as specialty_name,
                    pay.id as payment_id, pay.amount, pay.status as payment_status, pay.payment_date
                    FROM appointments a
                    JOIN users p_user ON a.patient_id = p_user.id
                    JOIN doctor_profiles dp ON a.doctor_id = dp.id
                    JOIN users d_user ON dp.user_id = d_user.id
                    JOIN specialties s ON dp.specialty_id = s.id
                    LEFT JOIN payments pay ON a.id = pay.appointment_id
                    WHERE a.status = 'completed' OR a.status = 'pending'
                    ORDER BY a.appointment_date DESC, a.time_slot DESC
                    LIMIT 100";
            $stmt = $db->query($sql);
            $data['appointments'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } elseif ($view === 'appointments') {
            // Get appointments for room allocation
            $sql = "SELECT a.*, 
                    p.first_name as patient_first, p.last_name as patient_last,
                    u_doc.first_name as doctor_first, u_doc.last_name as doctor_last,
                    s.name as specialty_name
                    FROM appointments a
                    JOIN users p ON a.patient_id = p.id
                    JOIN doctor_profiles dp ON a.doctor_id = dp.id
                    JOIN users u_doc ON dp.user_id = u_doc.id
                    JOIN specialties s ON dp.specialty_id = s.id
                    WHERE a.status IN ('pending', 'confirmed') 
                    ORDER BY a.appointment_date ASC, a.time_slot ASC";
            $stmt = $db->query($sql);
            $data['appointments'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } elseif ($view === 'payments') {
            // Get all payments
            $sql = "SELECT p.*, a.appointment_date, a.time_slot,
                    u.first_name, u.last_name
                    FROM payments p
                    JOIN appointments a ON p.appointment_id = a.id
                    JOIN users u ON a.patient_id = u.id
                    ORDER BY p.payment_date DESC
                    LIMIT 100";
            $stmt = $db->query($sql);
            $data['payments'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        $this->view('dashboard/receptionist', $data);
    }

    public function users()
    {
        $userModel = $this->model('User');
        $query = $_GET['q'] ?? '';

        // This is a bit tricky if User model doesn't have a search method yet.
        // For simplicity, let's keep the raw query for now but move it to Model eventually.
        $db = (new \App\Config\Database())->getConnection();
        if ($query) {
            $stmt = $db->prepare("SELECT * FROM users WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?) AND role='patient' ORDER BY created_at DESC");
            $term = "%$query%";
            $stmt->execute([$term, $term, $term]);
        } else {
            $stmt = $db->query("SELECT * FROM users WHERE role='patient' ORDER BY created_at DESC");
        }

        $users = $stmt->fetchAll();
        $this->view('dashboard/admin_users', ['users' => $users, 'query' => $query]);
    }

    public function doctors()
    {
        $doctorModel = $this->model('DoctorProfile');
        $doctors = $doctorModel->getAllDoctors();
        $this->view('dashboard/admin_doctors', ['doctors' => $doctors]);
    }

    public function staff()
    {
        $staffModel = $this->model('Staff');
        $query = $_GET['q'] ?? '';
        $staff = $staffModel->getAll($query);
        $this->view('dashboard/admin_staff', ['staff' => $staff, 'query' => $query]);
    }

    public function feedback()
    {
        $inquiryModel = $this->model('Inquiry');
        $feedbacks = $inquiryModel->getAll();
        $this->view('dashboard/admin_feedback', ['feedbacks' => $feedbacks]);
    }

    // Actions
    public function replyFeedback()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $response = $_POST['response'];
            $inquiryModel = $this->model('Inquiry');
            $inquiryModel->reply($id, $response);
            $this->redirect('dashboard/feedback');
        }
    }

    // ... Add other actions (delete user, etc) as needed.

}
