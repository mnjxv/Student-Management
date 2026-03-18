<?php
include_once 'conn.php';

$course_code = $_GET['course_code'];
$result = $conn->query("SELECT * FROM course WHERE course_code = '$course_code'");
$course = $result->fetch_assoc();

if (!$course) die("Course not found.<br><a href='courses.php'>Back</a>");
?>
<style>
    .form-group { margin-bottom: 14px; }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #222;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #bbb;
        border-radius: 6px;
        font-size: 13px;
        color: #333;
        background-color: #fff;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-group input:focus {
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

    .cu-btn-group {
        margin-top: 16px;
        border-top: 1px solid #e0e0e0;
        padding-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .cu-btn-submit {
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

    .cu-btn-submit:hover { background-color: #219a52; }

    .cu-btn-cancel {
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

    .cu-btn-cancel:hover { background-color: #1a252f; }
</style>

<h2 style="font-size:18px; font-weight:700; color:#111; margin-bottom:14px; border-bottom:1px solid #e5e5e5; padding-bottom:10px;">Edit Course</h2>

<div class="result-box" id="resultBox"></div>

<form id="courseUpdateForm">
    <input type="hidden" name="original_course_code" value="<?= htmlspecialchars($course['course_code']) ?>">

    <div class="form-group">
        <label>Course Code:</label>
        <input type="text" name="course_code" value="<?= htmlspecialchars($course['course_code']) ?>" required>
    </div>

    <div class="form-group">
        <label>Course Title:</label>
        <input type="text" name="course_title" value="<?= htmlspecialchars($course['course_title']) ?>" required>
    </div>

    <div class="form-group">
        <label>Units:</label>
        <input type="number" name="units" step="0.5" min="0.5" value="<?= htmlspecialchars($course['units']) ?>" required>
    </div>

    <div class="cu-btn-group">
        <button type="submit" class="cu-btn-submit" id="cuSubmitBtn">Update Course</button>
        <button type="button" class="cu-btn-cancel" id="cuCancelBtn">Cancel</button>
    </div>
</form>

<script>
    (function($) {
        $("#courseUpdateForm").off("submit").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "courseUpdateProcess.php",
                data: $(this).serialize(),
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        $("#resultBox").removeClass("error").addClass("success").text("✓ Course updated successfully!").show();
                        $("#cuSubmitBtn").hide();
                        $("#cuCancelBtn").text("Go Back").off("click").on("click", function() {
                            window.location.href = "courses.php";
                        });
                    } else {
                        $("#resultBox").removeClass("success").addClass("error").text("✗ " + data.error).show();
                    }
                },
                error: function() {
                    $("#resultBox").removeClass("success").addClass("error").text("✗ An unexpected error occurred.").show();
                }
            });
        });

        $("#cuCancelBtn").off("click").on("click", function() {
            $("#bg-modal").fadeOut();
            $("#modal").fadeOut();
        });
    })(jQuery);
</script>