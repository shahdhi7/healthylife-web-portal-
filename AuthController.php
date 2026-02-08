<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        $this->view('auth/login');
    }

    public function registerForm()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        $this->view('auth/register');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_email'] = $user['email'];

                // Log Activity
                $logger = new \App\Core\ActivityLogger();
                $logger->log($user['id'], 'Login', 'User logged in');

                $this->redirect('dashboard');
            } else {
                $this->view('auth/login', ['error' => 'Invalid email or password']);
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];
            $role = 'patient'; // Default registration is patient

            $userModel = $this->model('User');

            if ($userModel->emailExists($email)) {
                $this->view('auth/register', ['error' => 'Email already registered']);
                return;
            }

            if ($userModel->create($firstName, $lastName, $email, $password, $role, $gender, $dob)) {
                // Auto login after register or redirect to login?
                // Let's redirect to login for security/simplicity
                $this->redirect('login');
            } else {
                $this->view('auth/register', ['error' => 'Registration failed. Please try again.']);
            }
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('home');
    }
}
