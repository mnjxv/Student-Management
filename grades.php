<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-4.0.0.js" integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U=" crossorigin="anonymous"></script>
    <title>Grade Management</title>
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
        .btn-edit   { background-color: #1e40ff; color: white; }
        .btn-edit:hover   { background-color: #1a35cc; }
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
        <a href="#" class="btn btn-add">+ Add New Grade</a>
    </div>

    <div class="table-container">
        <h2 class="page-title">Grade List</h2>

        <div id="bg-modal"></div>
        <div id="modal"></div>

        <div id="bg-confirm"></div>
        <div id="confirm-modal">
            <p>Are you sure you want to delete this grade?</p>
            <div class="confirm-actions">
                <button id="confirm-yes" class="btn btn-delete">Delete</button>
                <button id="confirm-no" class="btn btn-cancel">Cancel</button>
            </div>
        </div>

        <table border="1" id="grade-table">
            <thead>
                <tr>
                    <th>Grade ID</th>
                    <th>Semester</th>
                    <th>School Year</th>
                    <th>Grade</th>
                    <th>Student Number</th>
                    <th>Course Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once 'conn.php';

                $grade_query = "SELECT g.*, 
                                        s.first_name, 
                                        s.last_name,
                                        c.course_title
                                FROM grade g
                                LEFT JOIN student s ON g.student_number = s.student_number
                                LEFT JOIN course c ON g.course_code = c.course_code
                                ORDER BY g.school_year DESC, g.semester, g.grade_id";
                $grade_result = $conn->query($grade_query);

                if ($grade_result->num_rows > 0) {
                    while ($grade = $grade_result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$grade['grade_id']}</td>
                            <td>{$grade['semester']}</td>
                            <td>{$grade['school_year']}</td>
                            <td><strong>{$grade['grade']}</strong></td>
                            <td>{$grade['student_number']}</td>
                            <td>{$grade['course_code']}</td>
                            <td class='actions'>
                                <a href='#' data-id='{$grade['grade_id']}' class='btn btn-edit'>Edit</a>
                                <a href='#' data-id='{$grade['grade_id']}' class='btn btn-delete'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='7' style='text-align:center; padding:40px; color:#666;'>
                                No grades found.
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
                $("#modal").load("gradeForm.php");
            });

            $(".btn-edit").click(function (e) {
                e.preventDefault();
                let gradeId = $(this).data("id");
                $("#modal").html("");
                $("#bg-modal").fadeIn();
                $("#modal").fadeIn();
                $("#modal").load("gradeUpdateForm.php?grade_id=" + gradeId);
            });

            
            $(".btn-delete").click(function (e) {
                e.preventDefault();
                let gradeID = $(this).data("id");

                $("#bg-confirm").fadeIn();
                $("#confirm-modal").fadeIn();

                $("#confirm-yes").off("click").on("click", function () {
                    $("#bg-confirm").fadeOut();
                    $("#confirm-modal").fadeOut();

                    $("#modal").html("");
                    $("#bg-modal").fadeIn();
                    $("#modal").fadeIn();
                    $("#modal").load("gradeDeleteProcess.php?grade_id=" + gradeID);
                });

                $("#confirm-no").off("click").on("click", function () {
                    $("#bg-confirm").fadeOut();
                    $("#confirm-modal").fadeOut();
                });
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
    </script>

</body>
</html>