<?php
include_once 'conn.php';

$gradeId = $_GET['grade_id'] ?? '';

if (empty($gradeId)) die("Grade ID not specified.");

$result = $conn->query("SELECT * FROM grade WHERE grade_id = '$gradeId'");
$gradeData = $result->fetch_assoc();

if (!$gradeData) die("Grade not found.");

$students_result = $conn->query("
    SELECT student_number, 
    CONCAT(student_number, ' - ', last_name, ', ', first_name) as student_name 
    FROM student 
    ORDER BY last_name, first_name
");

$courses_result = $conn->query("
    SELECT course_code, course_title 
    FROM course 
    ORDER BY course_code
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Grade</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .modal {
            background: white;
            border-radius: 10px;
            padding: 30px 25px 20px 25px;
            width: 100%;
        }

        .modal h2 {
            font-size: 18px;
            font-weight: 700;
            color: #111;
            margin-bottom: 14px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 0;
        }

        .form-group { margin-bottom: 14px; }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #222;
            margin-bottom: 5px;
        }

        .form-group label.required::after {
            content: " *";
            color: #e74c3c;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #bbb;
            border-radius: 6px;
            font-size: 13px;
            color: #333;
            background-color: #fff;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #27ae60;
        }

        .result-box {
            display: none;
            border-radius: 6px;
            padding: 10px 13px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 14px;
        }

        .result-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .result-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .btn-group {
            margin-top: 16px;
            border-top: 1px solid #e0e0e0;
            padding-top: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 11px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
        }

        .btn-submit:hover { background-color: #219a52; }

        .btn-cancel {
            display: block;
            width: 100%;
            padding: 11px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn-cancel:hover { background-color: #1a252f; }
    </style>
</head>
<body>

<div class="modal">
    <h2>Edit Grade</h2>

    <div class="result-box" id="resultBox"></div>

    <form id="gradeUpdateForm" action="gradeUpdateProcess.php" method="POST">
        <input type="hidden" name="grade_id" value="<?= $gradeData['grade_id'] ?>">

        <div class="form-grid">
            <div class="form-group">
                <label class="required">Student</label>
                <select name="student_number" required>
                    <option value="">Select Student</option>
                    <?php while ($student = $students_result->fetch_assoc()): ?>
                        <option value="<?= $student['student_number'] ?>"
                            <?= $student['student_number'] == $gradeData['student_number'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($student['student_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="required">Course</label>
                <select name="course_code" required>
                    <option value="">Select Course</option>
                    <?php while ($course = $courses_result->fetch_assoc()): ?>
                        <option value="<?= $course['course_code'] ?>"
                            <?= $course['course_code'] == $gradeData['course_code'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($course['course_code']) ?> - <?= htmlspecialchars($course['course_title']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="required">Grade</label>
                <input type="number" name="grade" step="0.01" min="1.0" max="5.0"
                       value="<?= $gradeData['grade'] ?>" required>
            </div>

            <div class="form-group">
                <label class="required">School Year</label>
                <input type="text" name="school_year"
                       value="<?= $gradeData['school_year'] ?>" required>
            </div>

            <div class="form-group">
                <label class="required">Semester</label>
                <select name="semester" required>
                    <option value="">Select Semester</option>
                    <option value="1st" <?= $gradeData['semester'] == '1st' ? 'selected' : '' ?>>1st Semester</option>
                    <option value="2nd" <?= $gradeData['semester'] == '2nd' ? 'selected' : '' ?>>2nd Semester</option>
                    <option value="Summer" <?= $gradeData['semester'] == 'Summer' ? 'selected' : '' ?>>Summer</option>
                </select>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit" id="submitBtn">Update Grade</button>
            <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {

        $("#gradeUpdateForm").submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "gradeUpdateProcess.php",
                data: $(this).serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $("#resultBox").removeClass("error").addClass("success").text("✓ Grade updated successfully!").show();
                        $("#submitBtn").hide();
                        $("#cancelBtn").text("Go Back").off("click").on("click", function () {
                            window.location.href = "grades.php";
                        });
                    } else {
                        $("#resultBox").removeClass("success").addClass("error").text("✗ " + data.error).show();
                    }
                },
                error: function () {
                    $("#resultBox").removeClass("success").addClass("error").text("✗ An unexpected error occurred.").show();
                }
            });
        });

        $("#cancelBtn").click(function () {
            $("#bg-modal").fadeOut();
            $("#modal").fadeOut();
        });

    });
</script>

</body>
</html>