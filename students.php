<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-4.0.0.js" integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U=" crossorigin="anonymous"></script>
    <title>Student Management</title>
    <style>
        .container {
            text-align: right;
            padding: 15px;
        }

        .btn {
            padding: 5px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-add    { background-color: #219653; color: white; }
        .btn-add:hover    { background-color: #27ae60; }
        .btn-view   { background-color: #1e40ff; color: white; }
        .btn-view:hover   { background-color: #1a35cc; }
        .btn-edit   { background-color: #c9b700; color: white; }
        .btn-edit:hover   { background-color: #a89900; }
        .btn-delete { background-color: #c40000; color: white; }
        .btn-delete:hover { background-color: #c0392b; }
        .btn-cancel { background-color: #95a5a6; color: white; }
        .btn-cancel:hover { background-color: #7f8c8d; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .actions { white-space: nowrap; }

        #bg-modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }

        #modal {
            display: none;
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        #bg-confirm {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1001;
        }

        #confirm-modal {
            display: none;
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 8px;
            padding: 30px 40px;
            z-index: 1002;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            min-width: 320px;
        }

        #confirm-modal p {
            font-size: 16px;
            color: #333;
            margin-bottom: 24px;
        }

        .confirm-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }
    </style>
</head>

<body>

    <div class="nav">
        <ul>
            <li><a href="colleges.php">Colleges</a></li>
            <li><a href="programs.php">Programs</a></li>
            <li><a href="students.php">Students</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="grades.php">Grades</a></li>
        </ul>
    </div>

    <div class="container">
        <a href="#" class="btn btn-add">+ Add New Student</a>
    </div>

    <div class="table-container">
        <h2 class="page-title">Student List</h2>

        <div id="bg-modal"></div>
        <div id="modal"></div>

        <div id="bg-confirm"></div>
        <div id="confirm-modal">
            <p>Are you sure you want to delete this student?</p>
            <div class="confirm-actions">
                <button id="confirm-yes" class="btn btn-delete">Delete</button>
                <button id="confirm-no" class="btn btn-cancel">Cancel</button>
            </div>
        </div>

        <table border="1" id="student-table">
            <tr>
                <th>Student No.</th>
                <th>Full Name</th>
                <th>College</th>
                <th>Birthday</th>
                <th>Program</th>
                <th>Actions</th>
            </tr>
            <tbody>
                <?php
                include_once 'conn.php';

                $students = $conn->query("
                    SELECT s.*, p.program_name, c.college_name, c.college_code
                    FROM student s
                    LEFT JOIN program p ON s.program_code = p.program_code
                    LEFT JOIN college c ON p.college_code = c.college_code
                    ORDER BY s.student_number DESC
                ");

                if ($students->num_rows > 0) {
                    while ($student = $students->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$student['student_number']}</td>
                            <td>{$student['first_name']} {$student['middle_name']} {$student['last_name']}</td>
                            <td>{$student['college_code']}</td>
                            <td>{$student['birthday']}</td>
                            <td>{$student['program_name']}</td>
                            <td class='actions'>
                                <a href='#' data-id='{$student['student_number']}' class='btn btn-view'>View</a>
                                <a href='#' data-id='{$student['student_number']}' class='btn btn-edit'>Edit</a>
                                <a href='#' data-id='{$student['student_number']}' class='btn btn-delete'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='6' style='text-align:center; padding:40px; color:#666;'>
                                No students found.
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    
    <script>
        $(document).ready(function () {

            $(".btn-add").click(function (e) {
                e.preventDefault();
                $("#modal").html("");
                $("#bg-modal").fadeIn();
                $("#modal").fadeIn();
                $("#modal").load("studentForm.php");
            });

            $(".btn-delete").click(function (e) {
                e.preventDefault();

                let studentNumber = $(this).data("id");

                $("#bg-confirm").fadeIn();
                $("#confirm-modal").fadeIn();

                $("#confirm-yes").off("click").on("click", function () {
                    $("#bg-confirm").fadeOut();
                    $("#confirm-modal").fadeOut();

                    $("#modal").html("");
                    $("#bg-modal").fadeIn();
                    $("#modal").fadeIn();
                    $("#modal").load("studentDeleteProcess.php?id=" + studentNumber);
                });

                $("#confirm-no").off("click").on("click", function () {
                    $("#bg-confirm").fadeOut();
                    $("#confirm-modal").fadeOut();
                });
            });

            $(".btn-edit").click(function (e) {
                e.preventDefault();
                let studentId = $(this).data("id");
                $("#modal").html("");
                $("#bg-modal").fadeIn();
                $("#modal").fadeIn();
                $("#modal").load("studentUpdateForm.php?id=" + studentId);
            });

            $("#bg-modal").click(function () {
                $("#bg-modal").fadeOut();
                $("#modal").fadeOut();
            });

            $("#bg-confirm").click(function () {
                $("#bg-confirm").fadeOut();
                $("#confirm-modal").fadeOut();
            });

        });
        $(".btn-view").click(function (e) {
                e.preventDefault();
                let studentId = $(this).data("id");
                $("#modal").html("");
                $("#bg-modal").fadeIn();
                $("#modal").fadeIn();
                $("#modal").load("studentView.php?id=" + studentId);
            });
    </script>

</body>
</html>