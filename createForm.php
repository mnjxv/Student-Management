<?php include_once 'conn.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Grade</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .modal {
            background: white;
            border-radius: 8px;
            padding: 8px 6px 4px 6px;
            width: 100%;
        }

        .modal h2 {
            font-size: 14px;
            font-weight: 700;
            color: #111;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 0;
        }

        .form-group { margin-bottom: 0; }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: #222;
            margin-bottom: 3px;
        }

        .form-group label.required::after {
            content: " *";
            color: #e74c3c;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 11px;
            color: #333;
            background-color: #fff;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #27ae60;
        }

        .btn-group {
            margin-top: 10px;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 8px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
        }

        .btn-submit:hover { background-color: #219a52; }
        .btn-submit:disabled { background-color: #6dbb8f; cursor: not-allowed; }

        .btn-cancel {
            display: block;
            width: 100%;
            padding: 8px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn-cancel:hover { background-color: #1a252f; }

        .result-box {
            display: none;
            border-radius: 5px;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 8px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

<div class="modal">
    <h2>Add New Grade</h2>

    <div class="result-box" id="resultBox">&#10003; Grade added successfully!</div>

    <form id="addForm">
        <div class="form-grid">
            <div class="form-group">
                <label class="required">Student</label>
                <select name="student_number" required>
                    <option value="">Select Student</option>
                    <?php
                    $students = $conn->query("SELECT student_number, first_name, last_name FROM student ORDER BY last_name");
                    while ($student = $students->fetch_assoc()) {
                        echo "<option value='{$student['student_number']}'>" .
                             htmlspecialchars($student['student_number']) . " - " .
                             htmlspecialchars($student['first_name']) . " " .
                             htmlspecialchars($student['last_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="required">Course</label>
                <select name="course_code" required>
                    <option value="">Select Course</option>
                    <?php
                    $courses = $conn->query("SELECT course_code, course_title FROM course ORDER BY course_title");
                    while ($course = $courses->fetch_assoc()) {
                        echo "<option value='{$course['course_code']}'>" .
                             htmlspecialchars($course['course_code']) . " - " .
                             htmlspecialchars($course['course_title']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="required">Grade</label>
                <input type="number" name="grade" step="0.01" min="1.0" max="5.0" placeholder="e.g., 1.75" required>
            </div>

            <div class="form-group">
                <label class="required">School Year</label>
                <input type="text" name="school_year" placeholder="e.g., 2023-2024" required>
            </div>

            <div class="form-group">
                <label class="required">Semester</label>
                <select name="semester" required>
                    <option value="">Select Semester</option>
                    <option value="1st">1st Semester</option>
                    <option value="2nd">2nd Semester</option>
                    <option value="Summer">Summer</option>
                </select>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit" id="submitBtn">Add Grade</button>
            <a href="#" class="btn-cancel" id="cancelBtn">Cancel</a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {

        $("#addForm").submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "gradeCreateProcess.php",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function () {
                    $("#submitBtn").prop("disabled", true).text("Saving...");
                },
                success: function (data) {
                    if (data.success) {
                        $("#resultBox").show();
                        $("#addForm")[0].reset();
                        $("#submitBtn").hide();
                        $("#cancelBtn").text("Go Back").attr("href", "grades.php");
                    } else {
                        $("#submitBtn").prop("disabled", false).text("Add Grade");
                    }
                },
                error: function () {
                    $("#submitBtn").prop("disabled", false).text("Add Grade");
                }
            });
        });

        $(".btn-cancel").click(function (e) {
            e.preventDefault();
            $("#bg-modal").fadeOut();
            $("#modal").fadeOut();
        });

        $("#bg-modal").click(function () {
            $("#bg-modal").fadeOut();
            $("#modal").fadeOut();
        });

    });
</script>

</body>
</html>