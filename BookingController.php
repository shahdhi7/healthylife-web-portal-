<?php

namespace App\Controllers;

use App\Core\Controller;

class BookingController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        } elseif ($_SESSION['user_role'] !== 'patient') {
            // Basic access control: only patients book? Or admins too?
            // For now assume patients.
        }

        $doctorModel = $this->model('DoctorProfile');
        $specialtyModel = $this->model('Specialty');

        $doctors = $doctorModel->getAllDoctors();
        $specialties = $specialtyModel->getAll();

        // Pass pre-selected doctor if any
        $selectedDoctorId = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;

        $this->view('booking/book', [
            'doctors' => $doctors,
            'specialties' => $specialties,
            'selected_doctor' => $selectedDoctorId
        ]);
    }

    public function getSlots()
    {
        header('Content-Type: application/json');

        $doctorId = $_GET['doctor_id'] ?? null;
        $date = $_GET['date'] ?? null;

        if (!$doctorId || !$date) {
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        // Check availability from DB
        $availabilityModel = $this->model('DoctorAvailability');
        $dayOfWeek = date('l', strtotime($date));
        $doctorAvail = null;
        $availTable = $availabilityModel->getByDoctor($doctorId);
        foreach ($availTable as $a) {
            if ($a['day_of_week'] === $dayOfWeek) {
                $doctorAvail = $a;
                break;
            }
        }

        if (!$doctorAvail || !$doctorAvail['is_available']) {
            echo json_encode(['error' => 'Unavailable Date. Please book on another day.']);
            return;
        }

        // Define standard slots within doctor's hours
        $startH = intval(substr($doctorAvail['start_time'], 0, 2));
        $endH = intval(substr($doctorAvail['end_time'], 0, 2));

        $appointmentModel = $this->model('Appointment');
        $bookedCounts = $appointmentModel->getSlotCounts($doctorId, $date);

        $availableSlots = [];
        for ($h = $startH; $h < $endH; $h++) {
            $H = str_pad($h, 2, '0', STR_PAD_LEFT);
            $time = "$H:00:00";
            $label = date('h:i A', strtotime($time)) . ' - ' . date('h:i A', strtotime(($H + 1) . ':00:00'));

            $count = isset($bookedCounts[$time]) ? $bookedCounts[$time] : 0;
            $isFull = $count >= 5;

            $availableSlots[] = [
                'time' => $time,
                'label' => $label,
                'is_full' => $isFull,
                'booked' => $count
            ];
        }

        echo json_encode(['slots' => $availableSlots]);
    }

    public function confirm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $this->redirect('login');
            }

            $doctorId = $_POST['doctor_id'];
            $date = $_POST['date'];
            $timeSlot = $_POST['time_slot'];
            $patientId = $_SESSION['user_id'];

            $appointmentModel = $this->model('Appointment');
            if ($appointmentModel->create($patientId, $doctorId, $date, $timeSlot)) {
                // Success
                $this->view('booking/success');
            } else {
                // Failure (Full or Error)
                $this->view('booking/book', ['error' => 'Booking failed. Slot might be full.']);
            }
        }
    }
}
