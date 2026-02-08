<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected $table_name = "users";

    public function emailExists($email)
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function create($firstName, $lastName, $email, $password, $role, $gender, $dob)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (first_name, last_name, email, password_hash, role, gender, date_of_birth) 
                  VALUES (:fname, :lname, :email, :password, :role, :gender, :dob)";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":fname", $firstName);
        $stmt->bindParam(":lname", $lastName);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":gender", $gender);
        $stmt->bindParam(":dob", $dob);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($email, $password)
    {
        $query = "SELECT id, first_name, last_name, email, password_hash, role FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password_hash'])) {
                return $row;
            }
        }
        return false;
    }
    public function getCountByRole($role)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE role = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $role);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;

        $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }
}
