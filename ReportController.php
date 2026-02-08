<?php

namespace App\Controllers;

use App\Core\Controller;
use PDO;

class ReportController extends Controller
{
    private $db;

    public function __construct()
    {
        $database = new \App\Config\Database();
        $this->db = $database->getConnection();
    }

    public function monthly()
    {
        // Check admin access
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('dashboard');
        }

        // Get month and year from query params or use current
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');

        // Validate month and year
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);

        // Generate report data
        $reportData = [
            'month' => $month,
            'year' => $year,
            'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
            'appointments' => $this->getAppointmentStats($month, $year),
            'patients' => $this->getPatientStats($month, $year),
            'doctors' => $this->getDoctorStats($month, $year),
            'revenue' => $this->getRevenueStats($month, $year),
            'specialties' => $this->getSpecialtyStats($month, $year),
        ];

        $this->view('admin/monthly_report', ['report' => $reportData]);
    }

    private function getAppointmentStats($month, $year)
    {
        $stats = [];

        // Total appointments
        $sql = "SELECT COUNT(*) as total FROM appointments 
                WHERE MONTH(appointment_date) = ? AND YEAR(appointment_date) = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$month, $year]);
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // By status
        $sql = "SELECT status, COUNT(*) as count FROM appointments 
                WHERE MONTH(appointment_date) = ? AND YEAR(appointment_date) = ?
                GROUP BY status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$month, $year]);
        $stats['by_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Daily breakdown
        $sql = "SELECT DAY(appointment_date) as day, COUNT(*) as count 
                FROM appointments 
                WHERE MONTH(appointment_date) = ? AND YEAR(appointment_date) = ?
                GROUP BY DAY(appointment_date)
                ORDER BY day";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$month, $year]);
        $stats['daily'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    private function getPatientStats($month, $year)
    {
        $stats = [];

        // New patients registered this month
        $sql = "SELECT COUNT(*) as total FROM users 
                WHERE role = 'patient' 
                AND MONTH(created_at) = ? AND YEAR(created_at) = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$month, $year]);
        $stats['new_patients'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Unique patients with appointments
        $sql = "SELECT COUNT(DISTINCT patient_id) as total FROM appointments 
                WHERE MONTH(appointment_date) = ? AND YEAR(appointment_date) = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$month, $year]);
        $stats['active_patients'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        return $stats;
    }

    private function getDoctorStats($month, $year)
    {
        $stats = [];

        // Top doctors by appointment count
        $sql = "SELECT d.id, u.first_name, u.last_name, s.name as specialty, 
                COUNT(a.id) as appointment_count
                FROM doctor_profiles d
                JOIN users u ON d.user_id = u.id
                JOIN specialties s ON d.specialty_id = s.id
                LEFT JOIN appointments a ON a.doctor_id = d.id 
                    AND MONTH(a.appointment_date) = ? AND YEAR(a.appointment_date) = ?
                GROUP BY d.id, u.first_name, u.last_name, s.name
                ORDER BY appointment_count DESC
                LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$month, $year]);
        $stats['top_doctors'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    private function getRevenueStats($month, $year)
    {
        $stats = [];

        // Total revenue (if payments table exists)
        $sql = "SELECT COUNT(*) FROM information_schema.tables 
                WHERE table_schema = DATABASE() AND table_name = 'payments'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] > 0) {
            $sql = "SELECT 
                    SUM(amount) as total_revenue,
                    COUNT(*) as total_payments,
                    SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as paid_amount,
                    SUM(CASE WHEN status = 'unpaid' THEN amount ELSE 0 END) as pending_amount
                    FROM payments 
                    WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$month, $year]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Estimate based on appointments (placeholder)
            $sql = "SELECT COUNT(*) * 100 as estimated_revenue FROM appointments 
                    WHERE MONTH(appointment_date) = ? AND YEAR(appointment_date) = ?
                    AND status = 'completed'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$month, $year]);
            $stats['estimated_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['estimated_revenue'];
        }

        return $stats;
    }

    private function getSpecialtyStats($month, $year)
    {
        $sql = "SELECT s.name, COUNT(a.id) as appointment_count
                FROM specialties s
                LEFT JOIN doctor_profiles d ON s.id = d.specialty_id
                LEFT JOIN appointments a ON a.doctor_id = d.id 
                    AND MONTH(a.appointment_date) = ? AND YEAR(a.appointment_date) = ?
                GROUP BY s.id, s.name
                ORDER BY appointment_count DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$month, $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function export()
    {
        // Export as CSV
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="hospital_report_' . $year . '_' . $month . '.csv"');

        $output = fopen('php://output', 'w');

        fputcsv($output, ['Hospital Monthly Report']);
        fputcsv($output, ['Month', date('F Y', mktime(0, 0, 0, $month, 1, $year))]);
        fputcsv($output, []);

        // Appointments
        $stats = $this->getAppointmentStats($month, $year);
        fputcsv($output, ['APPOINTMENTS']);
        fputcsv($output, ['Total Appointments', $stats['total']]);
        fputcsv($output, []);
        fputcsv($output, ['Status', 'Count']);
        foreach ($stats['by_status'] as $status) {
            fputcsv($output, [$status['status'], $status['count']]);
        }
        fputcsv($output, []);

        // Patients
        $patientStats = $this->getPatientStats($month, $year);
        fputcsv($output, ['PATIENTS']);
        fputcsv($output, ['New Patients', $patientStats['new_patients']]);
        fputcsv($output, ['Active Patients', $patientStats['active_patients']]);
        fputcsv($output, []);

        // Top Doctors
        $doctorStats = $this->getDoctorStats($month, $year);
        fputcsv($output, ['TOP DOCTORS']);
        fputcsv($output, ['Doctor Name', 'Specialty', 'Appointments']);
        foreach ($doctorStats['top_doctors'] as $doctor) {
            fputcsv($output, [
                'Dr. ' . $doctor['first_name'] . ' ' . $doctor['last_name'],
                $doctor['specialty'],
                $doctor['appointment_count']
            ]);
        }

        fclose($output);
        exit;
    }
}
