<?php
include_once 'conn.php';

$college_code = $_GET['college_code'];
$result = $conn->query("SELECT * FROM college WHERE college_code = '$college_code'");
$college = $result->fetch_assoc();

if (!$college) die("College not found.<br><a href='colleges.php'>Back</a>");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit College</title>

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
        

        .form-group { margin-bottom: 14px;}

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
    <h2>Edit College</h2>

    <form action="collegeUpdateProcess.php" method="post">
        <input type="hidden" name="original_college_code" value="<?= $college['college_code'] ?>">

        <div class="form-group">
            <label>College Code:</label>
            <input type="text" name="college_code" value="<?= $college['college_code'] ?>" required>
        </div>

        <div class="form-group">
            <label>College Name:</label>
            <input type="text" name="college_name" value="<?= $college['college_name'] ?>" required>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit">Update College</button>
            <a href="colleges.php" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $(".btn-submit").click(function (e) {
            e.preventDefault();
            $("#modal").html("");
            $("#bg-modal").fadeIn();
            $("#modal").fadeIn();
            $("#modal").load("collegeUpdateProcess.php");
            });
        });
</script>
</body>
</html>