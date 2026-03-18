<?php
include_once "conn.php";

header('Content-Type: application/json');

$success = false;
$error   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_number = $_POST['student_number'];
    $first_name     = $_POST['first_name'];
    $middle_name    = $_POST['middle_name'];
    $last_name      = $_POST['last_name'];
    $birthday       = $_POST['birthday'];
    $program_code   = $_POST['program_code'];
    $details        = $_POST['details'] ?? '';

    $sql = "UPDATE student 
            SET first_name = '$first_name',
                middle_name = '$middle_name',
                last_name = '$last_name',
                birthday = '$birthday',
                program_code = '$program_code',
                details = '$details'
            WHERE student_number = '$student_number'";

    try {
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        $error = $e->getMessage();
    }
} else {
    $error = "Invalid request method.";
}

echo json_encode(['success' => false, 'error' => $error]);
?>