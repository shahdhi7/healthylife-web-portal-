<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Specialty extends Model
{
    protected $table_name = "specialties";

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
