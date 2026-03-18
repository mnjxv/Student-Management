<?php
ob_start();
header('Content-Type: application/json');
include_once "conn.php";
ob_clean();

$response = [
    'success' => false,
    'error'   => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $course_code          = trim($_POST['course_code'] ?? '');
    $course_title         = trim($_POST['course_title'] ?? '');
    $units                = trim($_POST['units'] ?? '');
    $original_course_code = trim($_POST['original_course_code'] ?? '');

    if (empty($course_code) || empty($course_title) || empty($units) || empty($original_course_code)) {
        $response['error'] = "All fields are required.";
    } else {
        $sql = "UPDATE course 
                SET course_code = '$course_code', 
                    course_title = '$course_title', 
                    units = '$units'
                WHERE course_code = '$original_course_code'";

        try {
            if ($conn->query($sql) === TRUE) {
                $response['success'] = true;
            }
        } catch (mysqli_sql_exception $e) {
            $response['error'] = $e->getMessage();
        }
    }
} else {
    $response['error'] = "Invalid request method.";
}

echo json_encode($response);
?>