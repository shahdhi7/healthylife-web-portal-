<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home');
    }

    public function services()
    {
        $this->view('services');
    }

    public function sendContact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];
            $userId = $_SESSION['user_id'] ?? null;

            $inquiryModel = $this->model('Inquiry');
            if ($inquiryModel->create($name, $email, $subject, $message, $userId)) {
                $this->redirect('home?msg=success#contact');
            } else {
                $this->redirect('home?msg=error#contact');
            }
        }
    }
}
