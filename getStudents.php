<?php
include_once "conn.php";

$students = $conn->query("
SELECT s.*, p.program_name 
FROM student s
LEFT JOIN program p ON s.program_code = p.program_code
");

foreach($students as $student){

echo "
<tr>
<td>{$student['student_number']}</td>
<td>{$student['first_name']}</td>
<td>{$student['middle_name']}</td>
<td>{$student['last_name']}</td>
<td>{$student['birthday']}</td>
<td>{$student['program_name']}</td>

<td>

<a href='#' class='btn btn-view' data-id='{$student['student_number']}'>View</a>

<a href='studentUpdateForm.php?id={$student['student_number']}' class='btn btn-update'>Update</a>

<a href='studentDeleteProcess.php?id={$student['student_number']}' class='btn btn-delete'>Delete</a>

</td>
</tr>
";
}
?>