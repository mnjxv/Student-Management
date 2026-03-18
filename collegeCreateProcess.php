<?php
include_once 'conn.php';

header('Content-Type: application/json');

$success = false;
$error   = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_code = $_POST['college_code'];
    $college_name = $_POST['college_name'];

    $sql = "INSERT INTO college (college_code, college_name) VALUES ('$college_code', '$college_name')";

    try {
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $error = "College code '$college_code' already exists. Please use a different code.";
        } else {
            $error = $e->getMessage();
        }
    }
} else {
    $error = "Invalid request method.";
}

echo json_encode(['success' => false, 'error' => $error]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $success ? "College Added" : "Error"; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        

        .card {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }

        .card-header {
            background-color: #219653;
            color: #ffffff;
            padding: 16px 20px;
            font-size: 16px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
            font-size: 15px;
            color: #333;
        }

        .card-body p.error-detail {
            color: #c0392b;
            font-size: 13px;
            line-height: 1.5;
        }

        .card-footer {
            padding: 12px 20px;
            border-top: 1px solid #e5e7eb;
            text-align: right;
        }

        .btn {
            display: inline-block;
            padding: 8px 24px;
            background-color: #219653;
            color: white;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn:hover { background-color: #27ae60; }
    </style>
</head>
<body>

    <div class="card">
        <div class="card-header">
            <?php echo $success ? "Success" : "Error"; ?>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
                <p>College added successfully!</p>
            <?php else: ?>
                <p class="error-detail"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <a href="colleges.php" class="btn">Go Back</a>
        </div>
    </div>

</body>
</html>