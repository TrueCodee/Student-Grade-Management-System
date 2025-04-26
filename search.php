<?php
include 'db_connect.php';

$search_result = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];

    // Use prepared statement
    $sql_name = "SELECT student_name FROM name_table WHERE student_id = ?";
    $stmt = $conn->prepare($sql_name);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result_name = $stmt->get_result();

    if ($result_name->num_rows > 0) {
        $row_name = $result_name->fetch_assoc();
        $student_name = htmlspecialchars($row_name['student_name']);

        $sql_courses = "SELECT course_code, 
                        ROUND((test1 * 0.2) + (test2 * 0.2) + (test3 * 0.2) + (final_exam * 0.4), 1) AS final_grade 
                        FROM course_table WHERE student_id = ?";
        $stmt2 = $conn->prepare($sql_courses);
        $stmt2->bind_param("i", $student_id);
        $stmt2->execute();
        $result_courses = $stmt2->get_result();

        $search_result = "<h3>Results for: $student_name</h3>
                          <table border='1'>
                          <tr><th>Course Code</th><th>Final Grade</th></tr>";

        while ($row = $result_courses->fetch_assoc()) {
            $search_result .= "<tr><td>{$row['course_code']}</td><td>{$row['final_grade']}</td></tr>";
        }
        $search_result .= "</table>";

        $stmt2->close();
    } else {
        $search_result = "<p style='color:red;'>No student found with ID: $student_id</p>";
    }

    $stmt->close();
}
?>
