<?php

namespace App\Controllers;

use App\Core\Controller;
use PDO;

class BillingController extends Controller
{
    private $db;

    public function __construct()
    {
        $database = new \App\Config\Database();
        $this->db = $database->getConnection();
    }

    public function generateInvoice()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentId = $_POST['appointment_id'];
            $amount = $_POST['amount'];
            $paymentMethod = $_POST['payment_method'] ?? 'cash';

            $paymentModel = $this->model('Payment');

            // Check if payment already exists
            $existing = $paymentModel->getByAppointment($appointmentId);
            if ($existing) {
                $this->redirect('dashboard?view=billing&error=exists');
                return;
            }

            if ($paymentModel->create($appointmentId, $amount, $paymentMethod, 'unpaid')) {
                $this->redirect('dashboard?view=billing&success=created');
            } else {
                $this->redirect('dashboard?view=billing&error=failed');
            }
        }
    }

    public function processPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentId = $_POST['payment_id'];
            $status = $_POST['status'] ?? 'paid';

            $paymentModel = $this->model('Payment');

            if ($paymentModel->updateStatus($paymentId, $status)) {
                $this->redirect('dashboard?view=billing&success=paid');
            } else {
                $this->redirect('dashboard?view=billing&error=failed');
            }
        }
    }
}
