<?php
include_once "conn.php";

if (isset($_POST['old_program_code'], $_POST['program_code'], $_POST['program_name'], $_POST['college_code'])) {

    $old_program_code = $_POST['old_program_code'];
    $program_code = $_POST['program_code'];
    $program_name = $_POST['program_name'];
    $college_code = $_POST['college_code'];

    $sql = "UPDATE program 
            SET program_code = '$program_code', 
                program_name = '$program_name', 
                college_code = '$college_code'
            WHERE program_code = '$old_program_code'";

    $result = $conn->query($sql);

} else {
    die("Invalid request.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <?php if ($result) { ?>
        <div class="card-header">Success</div>
        <div class="card-body">
            <p>Program Updated successfully!</p>
        </div>
    <?php } else { ?>
        <div class="card-header" style="background:#c0392b;">Error</div>
        <div class="card-body">
            <p>Update failed. <?php echo $conn->error; ?></p>
        </div>
    <?php } ?>

    <div class="card-footer">
        <a href="programs.php" class="btn">Go Back</a>
    </div>
</div>

</body>
</html>