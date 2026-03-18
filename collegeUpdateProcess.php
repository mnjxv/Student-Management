<?php
include_once "conn.php";

$college_code = $_POST['college_code'];
$college_name = $_POST['college_name'];
$original_college_code = $_POST['original_college_code'];

$sql = "UPDATE college 
        SET college_code = '$college_code', 
            college_name = '$college_name' 
        WHERE college_code = '$original_college_code'";

$success = false;
$error = "";

try {
    if ($conn->query($sql) === TRUE) {
        $success = true;
    }
} catch (mysqli_sql_exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Updated</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .modal {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .modal-header {
            background-color: #1e40ff;
            color: #ffffff;
            padding: 16px 20px;
            font-size: 16px;
            font-weight: 600;
        }

        .modal-header.error {
            background-color: #c0392b;
        }

        .modal-body {
            padding: 20px;
            font-size: 15px;
            color: #333;
        }

        .modal-body p.error-detail {
            color: #c0392b;
            font-size: 13px;
            line-height: 1.5;
        }

        .modal-footer {
            padding: 12px 20px;
            border-top: 1px solid #e5e7eb;
            text-align: right;
        }

        .btn-edit   { background-color: #1e40ff; color: white; }
        .btn-edit:hover   { background-color: #1a35cc; }
        .btn-delete { background-color: #c40000; color: white; }
        .btn-delete:hover { background-color: #c0392b; }
    </style>
</head>
<body>

    <div class="modal">
        <div class="modal-header <?= $success ? '' : 'error' ?>">
            <?= $success ? "Success" : "Error"; ?>
        </div>
        <div class="modal-body">
            <?php if ($success): ?>
                <p>College updated successfully!</p>
            <?php else: ?>
                <p class="error-detail"><?= $error ?></p>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <a href="colleges.php" class="btn">Go Back</a>
        </div>
    </div>

</body>
</html>