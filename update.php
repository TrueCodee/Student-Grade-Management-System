<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_code = $_POST['course_code'];
    $grade_type = $_POST['grade_type'];
    $new_grade = $_POST['new_grade'];

    // Validate grade input: ensure it's numeric and within 0-100 range
    if (!is_numeric($new_grade) || $new_grade < 0 || $new_grade > 100) {
        echo "<script>
                alert('Error: Grade must be between 0 and 100!');
                window.location.href='dashboard.php';
              </script>";
        exit();
    }

    // Allow only valid columns
    $allowed_columns = ["test1", "test2", "test3", "final_exam"];
    if (!in_array($grade_type, $allowed_columns)) {
        echo "<script>
                alert('Error: Invalid grade type selected.');
                window.location.href='dashboard.php';
              </script>";
        exit();
    }

    // Check if the student is actually enrolled in the course
    $sql_check = "SELECT * FROM course_table WHERE student_id = ? AND course_code = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $student_id, $course_code);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        $stmt_check->close();
        echo "<script>
                alert('Error: Student is not enrolled in this course.');
                window.location.href='dashboard.php';
            </script>";
        exit();
}


    // prepared statement for security
    $sql_update = "UPDATE course_table 
                   SET $grade_type = ? 
                   WHERE student_id = ? AND course_code = ?";

    $stmt = $conn->prepare($sql_update);

    if (!$stmt) {
        echo "<script>
                alert('Error: Unable to process request.');
                window.location.href='dashboard.php';
              </script>";
        exit();
    }

    // Bind parameters
    $stmt->bind_param("iss", $new_grade, $student_id, $course_code);

    if ($stmt->execute()) {
        echo "<script>
                alert('Success: Grade updated successfully!');
                window.location.href='dashboard.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error updating record: " . $stmt->error . "');
                window.location.href='dashboard.php';
              </script>";
    }

    $stmt->close();
}
?>
