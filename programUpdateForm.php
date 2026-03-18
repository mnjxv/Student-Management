<?php
include_once 'conn.php';

$program_code = $_GET['program_code'] ?? '';

if (empty($program_code)) {
    die("Error: Program code not specified.<br><a href='programs.php'>Back to Program List</a>");
}

$stmt = $conn->prepare("SELECT * FROM program WHERE program_code = ?");
$stmt->bind_param("s", $program_code);
$stmt->execute();
$programResult = $stmt->get_result();

if ($programResult->num_rows == 0) {
    die("Program not found.<br><a href='programs.php'>Back to Program List</a>");
}

$program = $programResult->fetch_assoc();

$colleges_result = $conn->query("SELECT college_code, college_name FROM college ORDER BY college_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Program</title>
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

        .form-group input,
        .form-group select {
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
        .form-group select:focus {
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
    <h2>Update Program</h2>

    <?php if (isset($_GET['error'])): ?>
        <div style="background-color: #fff3cd; color: #856404; padding: 12px 15px; border-left: 4px solid #ffc107; margin-bottom: 14px; border-radius: 4px;">
            Please fill all required fields correctly.
        </div>
    <?php endif; ?>

    <form action="programUpdateProcess.php" method="POST" id="updateForm">
        <input type="hidden" name="original_program_code" value="<?= htmlspecialchars($program['program_code']) ?>">

        <div class="form-grid">
            <div class="form-group">
                <label class="required">Program Code</label>
                <input type="text" name="program_code" value="<?= htmlspecialchars($program['program_code']) ?>" required
                       placeholder="Enter program code" maxlength="15">
            </div>

            <div class="form-group">
                <label class="required">Program Name</label>
                <input type="text" name="program_name" value="<?= htmlspecialchars($program['program_name']) ?>" required
                       placeholder="Enter program name" maxlength="100">
            </div>

            <div class="form-group">
                <label class="required">College</label>
                <select name="college_code" required>
                    <option value="">Select a college</option>
                    <?php while ($college = $colleges_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($college['college_code']) ?>"
                            <?= ($college['college_code'] == $program['college_code']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($college['college_name']) ?> (<?= htmlspecialchars($college['college_code']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-submit">Update Program</button>
            <a href="programs.php" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<?php $conn->close(); ?>
</body>
</html>