<?php
include_once "conn.php";

$success = false;
$error   = "";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_number = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM student WHERE student_number = ?");
        $stmt->bind_param("s", $student_number);
        $stmt->execute();
        $stmt->close();
        $success = true;
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            $error = "Cannot delete this student because they have grades linked to them. Please delete those grades first.";
        } else {
            $error = $e->getMessage();
        }
    }
} else {
    $error = "No student ID specified.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $success ? "Student Deleted" : "Error"; ?></title>
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
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 8px;
        }

        .modal h2.success { color: #155724; }
        .modal h2.error   { color: #c0392b; }

        .modal p {
            font-size: 12px;
            color: #333;
        }

        .modal p.error-detail {
            color: #c0392b;
            font-size: 11px;
            line-height: 1.4;
        }

        .btn-group {
            margin-top: 10px;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .btn-back {
            display: block;
            width: 100%;
            padding: 8px;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn-back.success { background-color: #27ae60; }
        .btn-back.success:hover { background-color: #219a52; }
        .btn-back.error   { background-color: #c0392b; }
        .btn-back.error:hover { background-color: #a93226; }
    </style>
</head>
<body>

<div class="modal">
    <h2 class="<?= $success ? 'success' : 'error' ?>">
        <?= $success ? "Success" : "Error" ?>
    </h2>

    <?php if ($success): ?>
        <p>Student deleted successfully!</p>
    <?php else: ?>
        <p class="error-detail"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="btn-group">
        <a href="students.php" class="btn-back <?= $success ? 'success' : 'error' ?>">Go Back</a>
    </div>
</div>

</body>
</html>