<?php
include_once 'conn.php';

$grade_id = $_GET['grade_id'];

$result = $conn->query(
    "SELECT * FROM grade WHERE grade_id = '$grade_id'"
);

$course = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Grade</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #e6e6e6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); justify-content: center;}
        .student-form { padding: 30px; }

        .form-grid {  display: grid;  grid-template-columns: 1fr 1fr;  gap: 25px;  margin-bottom: 30px; }
        .form-group {  display: flex;  flex-direction: column; }
        .form-group label {  display: block;  margin-bottom: 8px;  font-weight: 600;  color: #333; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;}
        .form-group label.required::after {  content: " *";  color: red;}
        .form-group input, .form-group select {  width: 100%;  padding: 12px 15px;  border: 1px solid #ddd;  border-radius: 6px;  font-size: 16px; }
        .form-actions { display: flex; justify-content: space-between; border-top: 1px solid #ccc; padding-top: 20px; }
        .btn { padding: 6px 18px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; color: white; }
        .btn-cancel { background: #7f8c8d; }
        .btn-add { background: #27ae60; }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-actions { flex-direction: column; gap: 10px; }
            .btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

<div class="container">
   <form action="gradeUpdateProcess.php" method="post" class="grade-form">

   
    <input type="hidden" name="old_grade_id"
           value="<?= $course['grade_id'] ?>">
    <div class="form-grid">
        <div class="form-group">
            <label class="required">Grade ID</label>
            <input type="text" name="grade_id"
                   value="<?= $course['grade_id'] ?>" required>
        </div>

        <div class="form-group">
                <label class="required">Semester</label>
                <select name="semester" required placeholder="e.g. 1st Semester" style="color: #7f8c8d;">
                    <option value="">Select Semester</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                    <option value="Summer">Summer</option>
                </select>
        </div>

        <div class="form-group">
                <label class="required">School Year</label>
                <select name="school_year" required placeholder="e.g. 2023-2024" style="color: #7f8c8d;">
                    <option value="">Select School Year</option>
                    <option value="2022-2023">2022-2023</option>
                    <option value="2023-2024">2023-2024</option>
                    <option value="2024-2025">2024-2025</option>
                    <option value="2025-2026">2025-2026</option>
                </select>
        </div>

         <div class="form-group">
                <label class="required">Grade</label>
                <input type="text"  name="grade" value="<?= $course['grade'] ?>" required>
        </div>

         <div class="form-group">
                <label class="required">Student Number</label>
                <input type="text"  name="student_number" value="<?= $course['student_number'] ?>" required>
        </div>

         <div class="form-group">
                <label class="required">Course Code</label>
                <input type="text"  name="course_code" value="<?= $course['course_code'] ?>" required>
        </div>
    </div>

    <div class="form-actions">
        <a href="grades.php" class="btn btn-cancel">Cancel</a>
        <button type="submit" class="btn btn-add">Edit Grade</button>
    </div>
</form>
</div>


</body>
</html>