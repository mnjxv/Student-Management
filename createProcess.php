<?php
include_once 'conn.php';

$student_number = $_POST ['student_number'];
$first_name = $_POST ['first_name'];
$middle_name = $_POST ['middle_name'];
$last_name = $_POST ['last_name'];
$gender = $_POST ['gender'];
$birthday = $_POST ['birthday'];
$details = $_POST ['details'];
$program_code = $_POST ['program_code'];

$conn->query("INSERT INTO student 
VALUES ('$student_number', '$first_name', '$middle_name', '$last_name', '$gender', '$birthday', '$details', '$program_code')");
?>

Added!
<a href="students.php"> Go Back.</a>