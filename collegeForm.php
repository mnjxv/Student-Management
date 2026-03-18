<?php include_once 'conn.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add College</title>
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
            grid-template-columns: 1fr;
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

        .form-group input {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 11px;
            color: #333;
            background-color: #fff;
            font-family: inherit;
        }

        .form-group input:focus {
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
    <h2>Add New College</h2>

    <div class="result-box" id="resultBox"></div>

    <form id="addForm">
        <div class="form-grid">
            <div class="form-group">
                <label class="required">College Code</label>
                <input type="text" name="college_code" placeholder="Enter college code" required>
            </div>

            <div class="form-group">
                <label class="required">College Name</label>
                <input type="text" name="college_name" placeholder="Enter college name" required>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit" id="submitBtn">Add College</button>
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
                url: "collegeCreateProcess.php",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function () {
                    $("#submitBtn").prop("disabled", true).text("Saving...");
                },
                success: function (data) {
                    $("#resultBox").show();
                    if (data.success) {
                        $("#resultBox").removeClass("error").addClass("success").text("✓ College added successfully!");
                        $("#addForm")[0].reset();
                        $("#submitBtn").hide();
                        $("#cancelBtn").text("Go Back").attr("href", "colleges.php");
                    } else {
                        $("#resultBox").removeClass("success").addClass("error").text("✗ " + data.error);
                        $("#submitBtn").prop("disabled", false).text("Add College");
                    }
                },
                error: function () {
                    $("#resultBox").show().removeClass("success").addClass("error").text("✗ An unexpected error occurred.");
                    $("#submitBtn").prop("disabled", false).text("Add College");
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