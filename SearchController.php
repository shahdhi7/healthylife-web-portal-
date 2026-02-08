<?php

namespace App\Controllers;

use App\Core\Controller;

class SearchController extends Controller
{
    public function index()
    {
        $query = $_GET['q'] ?? '';

        // Use standard PDO model to search
        // We can create a generic SearchModel or just use raw query in controller for speed in this implementation
        // Let's assume we search Doctos and Services (static list for services)

        $results = [];

        if ($query) {
            $db = new \App\Config\Database();
            $conn = $db->getConnection();

            // Search Doctors
            $docSql = "SELECT d.id, u.first_name, u.last_name, s.name as specialty 
                       FROM doctor_profiles d 
                       JOIN users u ON d.user_id = u.id 
                       JOIN specialties s ON d.specialty_id = s.id 
                       WHERE u.first_name LIKE ? OR u.last_name LIKE ? OR s.name LIKE ?";
            $stmt = $conn->prepare($docSql);
            $term = "%$query%";
            $stmt->execute([$term, $term, $term]);
            $doctors = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($doctors as $d) {
                $results[] = [
                    'type' => 'Doctor',
                    'title' => "Dr. " . $d['first_name'] . " " . $d['last_name'],
                    'description' => "Specialist in " . $d['specialty'],
                    'link' => "doctors/details?id=" . $d['id']
                ];
            }

            // Search Services (Hardcoded for now as they are static in view)
            $services = ['Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Dermatology'];
            foreach ($services as $s) {
                if (stripos($s, $query) !== false) {
                    $results[] = [
                        'type' => 'Service',
                        'title' => $s,
                        'description' => 'Medical Department',
                        'link' => 'services'
                    ];
                }
            }
        }

        $this->view('search/results', ['results' => $results, 'query' => $query]);
    }
}
