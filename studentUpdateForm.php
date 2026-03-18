<?php
include_once "conn.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Student ID not specified.<br><a href='students.php'>Back to Student List</a>");
}

$student_number = $_GET['id'];

$stmt = $conn->prepare("SELECT s.*, p.program_name FROM student s JOIN program p ON s.program_code = p.program_code WHERE s.student_number = ?");
$stmt->bind_param("s", $student_number);
$stmt->execute();
$studentResult = $stmt->get_result();

if ($studentResult->num_rows == 0) {
    die("Student not found.");
}

$student = $studentResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
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

        .form-group { margin-bottom: 0; }
        .form-group.full-width { grid-column: 1 / -1; }

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
        .form-group select,
        .form-group textarea {
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
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #27ae60;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
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
        .btn-submit:disabled { background-color: #6dbb8f; cursor: not-allowed; }

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

        .result-box {
            display: none;
            border-radius: 6px;
            padding: 10px 13px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 12px;
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
    </style>
</head>
<body>

<div class="modal">
    <h2>Edit Student</h2>

    <div class="result-box" id="resultBox"></div>

    <form id="editForm">
        <input type="hidden" name="student_number" value="<?= htmlspecialchars($student['student_number']) ?>">

        <div class="form-grid">
            <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required placeholder="Enter first name">
            </div>

            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="middle_name" value="<?= htmlspecialchars($student['middle_name']) ?>" placeholder="Enter middle name">
            </div>

            <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required placeholder="Enter last name">
            </div>

            <div class="form-group">
                <label class="required">Birthday</label>
                <input type="date" name="birthday" value="<?= htmlspecialchars($student['birthday']) ?>" required>
            </div>

            <div class="form-group full-width">
                <label class="required">Program</label>
                <select name="program_code" required>
                    <option value="">Select a program</option>
                    <?php
                    $programs = $conn->query("SELECT program_code, program_name FROM program");
                    while ($prog = $programs->fetch_assoc()) {
                        $selected = ($prog['program_code'] == $student['program_code']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($prog['program_code']) . "' $selected>" .
                             htmlspecialchars($prog['program_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group full-width">
                <label>Additional Details</label>
                <textarea name="details" placeholder="Enter any additional information"><?= htmlspecialchars($student['details'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit" id="submitBtn">Update Student</button>
            <a href="#" class="btn-cancel" id="cancelBtn">Cancel</a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {

        $("#editForm").submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "studentUpdateProcess.php",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function () {
                    $("#submitBtn").prop("disabled", true).text("Saving...");
                },
                success: function (data) {
                    $("#resultBox").show();
                    if (data.success) {
                        $("#resultBox").removeClass("error").addClass("success").text("✓ Student updated successfully!");
                        $("#submitBtn").hide();
                        $("#cancelBtn").text("Go Back").attr("href", "students.php");
                    } else {
                        $("#resultBox").removeClass("success").addClass("error").text("✗ " + data.error);
                        $("#submitBtn").prop("disabled", false).text("Update Student");
                    }
                },
                error: function () {
                    $("#resultBox").show().removeClass("success").addClass("error").text("✗ An unexpected error occurred.");
                    $("#submitBtn").prop("disabled", false).text("Update Student");
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