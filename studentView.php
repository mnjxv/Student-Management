<?php
include_once 'conn.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div style='padding:20px; text-align:center;'>";
    echo "<p style='color:red;'>Error: Student ID not specified.</p>";
    echo "<button onclick='$(\"#bg-modal, #modal\").fadeOut()' style='padding:8px 20px; background:#667eea; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px;'>Close</button>";
    echo "</div>";
    exit;
}

$student_number = $_GET['id'];

$stmt = $conn->prepare("
    SELECT s.*, 
           p.program_name, p.program_code, 
           c.college_name, c.college_code
    FROM student s 
    LEFT JOIN program p ON s.program_code = p.program_code 
    LEFT JOIN college c ON p.college_code = c.college_code 
    WHERE s.student_number = ?
");

$stmt->bind_param("s", $student_number);
$stmt->execute();
$studentResult = $stmt->get_result();

if ($studentResult->num_rows > 0) {
    $student = $studentResult->fetch_assoc();
    $full_name = htmlspecialchars(
        $student['first_name'] . " " . 
        (!empty($student['middle_name']) ? $student['middle_name'] . " " : "") . 
        $student['last_name']
    );
?>

<style>
    .sv-wrapper {
        padding: 24px 28px;
        font-family: 'Segoe UI', sans-serif;
        color: #333;
    }
    .sv-name {
        font-size: 1.45rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 6px 0;
    }
    .sv-meta {
        margin-bottom: 16px;
    }
    .sv-meta p {
        margin: 2px 0;
        font-size: 0.88rem;
        color: #555;
    }
    .sv-meta strong {
        font-weight: 600;
        color: #333;
    }
    .sv-meta em {
        font-style: italic;
    }
    .sv-details p {
        font-size: 0.93rem;
        line-height: 1.75;
        color: #444;
        margin: 0 0 10px 0;
    }
    .sv-footer {
        text-align: center;
        margin-top: 22px;
    }
    .sv-close-btn {
        padding: 10px 0;
        width: 100%;
        max-width: 665px;
        background: #4a4a8a;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        transition: background 0.2s;
    }
    .sv-close-btn:hover {
        background: #3b3b72;
    }
</style>

<div class="sv-wrapper">

    <h2 class="sv-name"><?= $full_name ?></h2>

    <div class="sv-meta">
        <p><strong>Program:</strong> <em><?= htmlspecialchars($student['program_name'] ?: 'No Program') ?> (<?= htmlspecialchars($student['program_code'] ?: 'N/A') ?>)</em></p>
        <p><strong>College:</strong> <em><?= htmlspecialchars($student['college_name'] ?: 'No College') ?> (<?= htmlspecialchars($student['college_code'] ?: 'N/A') ?>)</em></p>
    </div>

    <?php if (!empty($student['details'])): ?>
    <div class="sv-details">
        <p><?= nl2br(htmlspecialchars($student['details'])) ?></p>
    </div>
    <?php endif; ?>

    <div class="sv-footer">
        <button onclick="$('#bg-modal, #modal').fadeOut()" class="sv-close-btn">Close</button>
    </div>

</div>

<?php
} else {
    echo "<div style='padding:30px; text-align:center;'>";
    echo "<h3 style='color:#666; margin-bottom:15px;'>Student Not Found</h3>";
    echo "<p style='color:#999; margin-bottom:20px;'>No student found with ID: " . htmlspecialchars($student_number) . "</p>";
    echo "<button onclick='$(\"#bg-modal, #modal\").fadeOut()' style='padding:8px 20px; background:#667eea; color:white; border:none; border-radius:5px; cursor:pointer;'>Close</button>";
    echo "</div>";
}

$stmt->close();
$conn->close();
?>