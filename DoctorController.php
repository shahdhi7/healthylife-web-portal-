<?php

namespace App\Controllers;

use App\Core\Controller;

class DoctorController extends Controller
{
    public function index()
    {
        $doctorModel = $this->model('DoctorProfile');
        $specialtyModel = $this->model('Specialty');

        $doctors = $doctorModel->getAllDoctors();
        $specialties = $specialtyModel->getAll();

        $this->view('doctors', [
            'doctors' => $doctors,
            'specialties' => $specialties
        ]);
    }

    public function manageAvailability()
    {
        $role = $_SESSION['user_role'];
        if ($role !== 'doctor' && $role !== 'admin') {
            $this->redirect('dashboard');
        }

        $doctorModel = $this->model('DoctorProfile');
        $availabilityModel = $this->model('DoctorAvailability');

        // Handle doctor_id (admins provide it via query/post, doctors use their own)
        $doctorId = $_GET['doctor_id'] ?? $_POST['doctor_id'] ?? null;

        if ($role === 'doctor') {
            $doctorId = $doctorModel->getDoctorIdByUserId($_SESSION['user_id']);
        }

        if (!$doctorId) {
            echo "Error: Doctor ID missing.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day) {
                if (isset($_POST['available'][$day])) {
                    $availabilityModel->setAvailability(
                        $doctorId,
                        $day,
                        $_POST['start_time'][$day],
                        $_POST['end_time'][$day],
                        1
                    );
                } else {
                    $availabilityModel->setAvailability($doctorId, $day, '09:00:00', '17:00:00', 0);
                }
            }
            $target = $role === 'admin' ? 'dashboard/availability?doctor_id=' . $doctorId . '&success=1' : 'dashboard/availability?success=1';
            $this->redirect($target);
        }

        $availability = $availabilityModel->getByDoctor($doctorId);
        $this->view('dashboard/doctor_availability', [
            'availability' => $availability,
            'doctor_id' => $doctorId,
            'is_admin' => ($role === 'admin')
        ]);
    }

    public function details()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('doctors');
        }

        $doctorModel = $this->model('DoctorProfile');
        $doctor = $doctorModel->getDoctorById($id);

        if (!$doctor) {
            $this->redirect('doctors');
        }

        $this->view('doctors/details', ['doctor' => $doctor]);
    }
}
