function viewStudent(student_number) {
    $.ajax({
        url: "viewStudent.php",
        type: "get",
        data: {
            student_number: student_number,
        },
        success: (response) => {
            $("#bg-modal").show().click(() => {
                $("#bg-modal").hide();
                $("#modal").hide();
            });
            $("#modal").show().html(response);
            $("#close-modal").click(() => {
                $("#bg-modal").hide();
                $("#modal").hide();
            });
        }
    });
}

function editStudent() {
    $.ajax({
        url: "studentUpdateProcess.php",
        type: "post",
        data: $("#studentUpdateForm").serialize(),
        success: (response) => {
            if (response == "success") {
                alert("Student updated successfully");

                $("#bg-modal").hide();
                $("#modal").hide();

                
            } else {
                alert("Update failed");
            }
        }
    });
}
function editCollege() {
    $.ajax({
        url: "studentEditProcess.php",
        type: "post",
        data: $("#collegeUpdateForm").serialize(),
        success: (response) => {
            if (response == "success") {
                alert("College updated successfully");

                $("#bg-modal").hide();
                $("#modal").hide();

                
            } else {
                alert("Update failed");
            }
        }
    });
}


            