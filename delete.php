<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_code = $_POST['course_code'];

    $sql_delete = "DELETE FROM course_table WHERE student_id = ? AND course_code = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("is", $student_id, $course_code);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}
?>
