<?php

require_once '../app/Config/Database.php';

use App\Config\Database;

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Read the schema file
    $schemaFile = __DIR__ . '/../sql/schema.sql';
    if (!file_exists($schemaFile)) {
        die("Error: Schema file not found at $schemaFile");
    }

    $sql = file_get_contents($schemaFile);

    // Remove comments to avoid issues with basic splitting (basic regex)
    $sql = preg_replace('/--.*$/m', '', $sql);

    // Split by semicolon
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            try {
                $conn->exec($stmt);
                // echo "Executed: " . substr($stmt, 0, 50) . "...<br>";
            } catch (PDOException $e) {
                // Ignore "Table already exists" or similar non-fatal errors if we want idempotency, 
                // but for now let's just print them.
                echo "Error executing statement: " . $e->getMessage() . "<br>";
                echo "Statement: " . $stmt . "<br><hr>";
            }
        }
    }

    echo "<h1>Database Schema Updated Successfully!</h1>";

} catch (Exception $e) {
    echo "Critical Error: " . $e->getMessage();
}
