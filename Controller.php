<?php

namespace App\Core;

class Controller
{
    public function view($viewName, $data = [])
    {
        // Extract data variables to be available in view
        extract($data);

        // Convert 'home' to '../views/home.php'
        $viewPath = "../views/" . $viewName . ".php";

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View does not exist: " . $viewPath);
        }
    }

    public function model($model)
    {
        // Require model file
        require_once "../app/Models/" . $model . ".php";

        // Instantiate model
        $modelClass = "App\\Models\\" . $model;
        return new $modelClass();
    }

    public function redirect($url)
    {
        // If it's a relative URL (doesn't start with http or /), prefix with APPROOT
        if (!preg_match('~^(?:f|ht)tps?://~i', $url) && strpos($url, '/') !== 0) {
            $url = APPROOT . '/' . ltrim($url, '/');
        }
        header("Location: " . $url);
        exit;
    }
}
