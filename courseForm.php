<?php include_once 'conn.php'; ?>

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
        grid-template-columns: 1fr;
        gap: 14px;
        margin-bottom: 0;
    }

    .form-group { margin-bottom: 0; }

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

    .form-group input {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #bbb;
        border-radius: 6px;
        font-size: 13px;
        color: #333;
        background-color: #fff;
        font-family: inherit;
    }

    .form-group input:focus {
        outline: none;
        border-color: #27ae60;
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

<div class="modal">
    <h2>Add New Course</h2>

    <div class="result-box" id="resultBox"></div>

    <form id="addForm">
        <div class="form-grid">
            <div class="form-group">
                <label class="required">Course Code</label>
                <input type="text" name="course_code" placeholder="Enter course code" required>
            </div>

            <div class="form-group">
                <label class="required">Course Title</label>
                <input type="text" name="course_title" placeholder="Enter course title" required>
            </div>

            <div class="form-group">
                <label class="required">Units</label>
                <input type="number" name="units" placeholder="Enter number of units" min="1" max="6" step="1" required>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit" id="submitBtn">Add Course</button>
            <a href="#" class="btn-cancel" id="cancelBtn">Cancel</a>
        </div>
    </form>
</div>

<script>
(function($) {
    $("#addForm").off("submit").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "courseCreateProcess.php",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $("#submitBtn").prop("disabled", true).text("Saving...");
            },
            success: function(data) {
                $("#resultBox").show();
                if (data.success) {
                    $("#resultBox").removeClass("error").addClass("success").text("✓ Course added successfully!");
                    $("#addForm")[0].reset();
                    $("#submitBtn").hide();
                    $("#cancelBtn").text("Go Back").off("click").on("click", function(e) {
                        e.preventDefault();
                        window.location.href = "courses.php";
                    });
                } else {
                    $("#resultBox").removeClass("success").addClass("error").text("✗ " + data.error);
                    $("#submitBtn").prop("disabled", false).text("Add Course");
                }
            },
            error: function() {
                $("#resultBox").show().removeClass("success").addClass("error").text("✗ An unexpected error occurred.");
                $("#submitBtn").prop("disabled", false).text("Add Course");
            }
        });
    });

    $("#cancelBtn").off("click").on("click", function(e) {
        e.preventDefault();
        $("#bg-modal").fadeOut();
        $("#modal").fadeOut();
    });
})(jQuery);
</script>