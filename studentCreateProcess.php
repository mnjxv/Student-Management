<?php
include_once 'conn.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

$student_number = $_POST['student_number'];
$first_name     = $_POST['first_name'];
$middle_name    = $_POST['middle_name'];
$last_name      = $_POST['last_name'];
$gender         = $_POST['gender'];
$birthday       = $_POST['birthday'];
$details        = $_POST['details'];
$program_code   = $_POST['program_code'];

$sql = "INSERT INTO student (student_number, first_name, middle_name, last_name, gender, birthday, details, program_code) 
        VALUES ('$student_number', '$first_name', '$middle_name', '$last_name', '$gender', '$birthday', '$details', '$program_code')";

try {
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add student.']);
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        echo json_encode(['success' => false, 'error' => "Student number '$student_number' already exists."]);
    } else {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
exit;