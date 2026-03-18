<?php
include_once 'conn.php';

header('Content-Type: application/json');

$success = false;
$error   = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $program_code = $_POST['program_code'];
    $program_name = $_POST['program_name'];
    $college_code = $_POST['college_code'];

    $sql = "INSERT INTO program (program_code, program_name, college_code) 
            VALUES ('$program_code', '$program_name', '$college_code')";

    try {
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $error = "Program code '$program_code' already exists. Please use a different code.";
        } else {
            $error = $e->getMessage();
        }
    }
} else {
    $error = "Invalid request method.";
}

echo json_encode(['success' => false, 'error' => $error]);
?>