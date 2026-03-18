<?php
ob_start();
header('Content-Type: application/json');
include_once 'conn.php';
ob_clean();

ini_set('display_errors', 0);

$course_code  = trim($_POST['course_code'] ?? '');
$course_title = trim($_POST['course_title'] ?? '');
$units        = intval($_POST['units'] ?? 0);

if (empty($course_code) || empty($course_title) || $units < 1) {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit;
}

$check = $conn->prepare("SELECT course_code FROM course WHERE course_code = ?");
if (!$check) {
    echo json_encode(['success' => false, 'error' => 'DB error: ' . $conn->error]);
    exit;
}
$check->bind_param("s", $course_code);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'Course code already exists.']);
    exit;
}
$check->close();

$stmt = $conn->prepare("INSERT INTO course (course_code, course_title, units) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'DB prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssi", $course_code, $course_title, $units);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Course added successfully!']);
} else {
    echo json_encode(['success' => false, 'error' => 'Insert failed: ' . $stmt->error]);
}

$stmt->close();