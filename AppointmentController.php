<?php

namespace App\Controllers;

use App\Core\Controller;

class AppointmentController extends Controller
{

    public function complete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if doctor
            if ($_SESSION['user_role'] !== 'doctor') {
                $this->redirect('dashboard');
            }

            $appointmentId = $_POST['appointment_id'];
            $appointmentModel = $this->model('Appointment');
            $appointmentModel->updateStatus($appointmentId, 'completed');

            $target = $_POST['redirect'] ?? 'dashboard';
            $this->redirect($target);
        }
    }

    public function assign_room()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_SESSION['user_role'] !== 'receptionist' && $_SESSION['user_role'] !== 'admin') {
                $this->redirect('dashboard');
            }

            $appointmentId = $_POST['appointment_id'];
            $roomNumber = $_POST['room_number'];

            $appointmentModel = $this->model('Appointment');
            $appointmentModel->updateRoom($appointmentId, $roomNumber);

            $target = $_POST['redirect'] ?? 'dashboard';
            $this->redirect($target);
        }
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_SESSION['user_role'] !== 'doctor') {
                $this->redirect('dashboard');
            }

            $appointmentId = $_POST['appointment_id'];

            if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] == 0) {
                $targetDir = "uploads/";
                $serverPath = "../public/" . $targetDir;
                if (!file_exists($serverPath)) {
                    mkdir($serverPath, 0777, true);
                }

                $fileName = basename($_FILES["report_file"]["name"]);
                $dbFilePath = $targetDir . uniqid() . "_" . $fileName;
                $targetFilePath = $serverPath . basename($dbFilePath);

                // Move file
                if (move_uploaded_file($_FILES["report_file"]["tmp_name"], $targetFilePath)) {
                    $appointmentModel = $this->model('Appointment');
                    $conn = (new \App\Config\Database())->getConnection();
                    $query = "SELECT patient_id, doctor_id FROM appointments WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$appointmentId]);
                    $appt = $stmt->fetch(\PDO::FETCH_ASSOC);

                    if ($appt) {
                        $reportModel = $this->model('MedicalReport');
                        $reportModel->create($appt['patient_id'], $appt['doctor_id'], $dbFilePath, $fileName, $_POST['description'] ?? '');

                        // Mark as completed
                        $appointmentModel->updateStatus($appointmentId, 'completed');
                    }
                }
            }

            $target = $_POST['redirect'] ?? 'dashboard';
            $this->redirect($target);
        }
    }
}
