<?php
ob_start();
header('Content-Type: application/json');
include_once 'conn.php';
ob_clean();

$response = [
    'success' => false,
    'error'   => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $grade_id       = $_POST['grade_id'] ?? '';
    $student_number = trim($_POST['student_number'] ?? '');
    $course_code    = trim($_POST['course_code'] ?? '');
    $grade          = trim($_POST['grade'] ?? '');
    $school_year    = trim($_POST['school_year'] ?? '');
    $semester       = trim($_POST['semester'] ?? '');

    if (empty($grade_id) || empty($student_number) || empty($course_code) || empty($grade) || empty($school_year) || empty($semester)) {
        $response['error'] = "All fields are required.";
    } else {
        $stmt = $conn->prepare("
            UPDATE grade 
            SET student_number = ?, 
                course_code = ?, 
                grade = ?, 
                school_year = ?, 
                semester = ?
            WHERE grade_id = ?
        ");

        if (!$stmt) {
            $response['error'] = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("sssssi", $student_number, $course_code, $grade, $school_year, $semester, $grade_id);

            if ($stmt->execute()) {
                $response['success'] = true;
            } else {
                $response['error'] = $stmt->error;
            }

            $stmt->close();
        }
    }
} else {
    $response['error'] = "Invalid request method.";
}

echo json_encode($response);
?>