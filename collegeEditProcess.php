<?php
include_once "conn.php";

header('Content-Type: application/json');

$success = false;
$error   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $college_code     = $_POST['college_code'];
    $college_name     = $_POST['college_name'];
    $old_college_code = $_POST['old_college_code'];

    $sql = "UPDATE college 
            SET college_code = '$college_code', 
                college_name = '$college_name' 
            WHERE college_code = '$old_college_code'";

    try {
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $error = "College code '$college_code' already exists.";
        } else {
            $error = $e->getMessage();
        }
    }
} else {
    $error = "Invalid request method.";
}

echo json_encode(['success' => false, 'error' => $error]);
?>