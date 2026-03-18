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

    $student_number = trim($_POST['student_number'] ?? '');
    $course_code    = trim($_POST['course_code'] ?? '');
    $grade          = trim($_POST['grade'] ?? '');
    $school_year    = trim($_POST['school_year'] ?? '');
    $semester       = trim($_POST['semester'] ?? '');

    if (empty($student_number) || empty($course_code) || empty($grade) || empty($school_year) || empty($semester)) {
        $response['error'] = "All fields are required.";
        echo json_encode($response);
        exit();
    }

    if ($grade < 1.0 || $grade > 5.0) {
        $response['error'] = "Grade must be between 1.0 and 5.0.";
        echo json_encode($response);
        exit();
    }

    if (!preg_match('/^\d{4}-\d{4}$/', $school_year)) {
        $response['error'] = "School year must be in format YYYY-YYYY (e.g., 2023-2024).";
        echo json_encode($response);
        exit();
    }

    $check_student = $conn->query("SELECT student_number FROM student WHERE student_number = '$student_number'");
    if ($check_student->num_rows === 0) {
        $response['error'] = "Selected student does not exist.";
        echo json_encode($response);
        exit();
    }

    $check_course = $conn->query("SELECT course_code FROM course WHERE course_code = '$course_code'");
    if ($check_course->num_rows === 0) {
        $response['error'] = "Selected course does not exist.";
        echo json_encode($response);
        exit();
    }

    $check_duplicate = $conn->query("
        SELECT grade_id FROM grade 
        WHERE student_number = '$student_number' 
        AND course_code = '$course_code' 
        AND school_year = '$school_year' 
        AND semester = '$semester'
    ");

    if ($check_duplicate->num_rows > 0) {
        $response['error'] = "A grade already exists for this student, course, school year, and semester.";
        echo json_encode($response);
        exit();
    }

    $grade = (float) $grade;

    $max_id_result = $conn->query("SELECT MAX(grade_id) as max_id FROM grade");
    $max_row       = $max_id_result->fetch_assoc();
    $next_id       = ($max_row['max_id'] ? $max_row['max_id'] + 1 : 1);

    $sql = "INSERT INTO grade (grade_id, student_number, course_code, grade, school_year, semester) 
            VALUES ($next_id, '$student_number', '$course_code', $grade, '$school_year', '$semester')";

    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
    } else {
        $response['error'] = "Database error: " . $conn->error;
    }

} else {
    $response['error'] = "Invalid request method.";
}

echo json_encode($response);
?>