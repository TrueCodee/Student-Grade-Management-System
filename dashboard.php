<?php
session_start();
include 'db_connect.php';

// Redirect to login if no session exists
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Generate CSRF Token for security
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$username = $_SESSION['username'];

// Secure Search Query with Prepared Statements
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_input = trim($_GET['search']); // Remove spaces from input

    // If numeric, search Student ID with "Starts With"
    if (is_numeric($search_input)) {
        $search_term = $search_input . "%"; // Student ID must start with input
        $course_search_term = "INVALID"; // Prevents unwanted matches
    } else {
        $search_term = "INVALID"; // Prevents unwanted matches
        $course_search_term = "%" . $search_input . "%"; // Partial match for course codes
    }

    $sql_search = "SELECT c.student_id, n.student_name, c.course_code, 
                   c.test1, c.test2, c.test3, c.final_exam,
                   ROUND((c.test1 * 0.2) + (c.test2 * 0.2) + (c.test3 * 0.2) + (c.final_exam * 0.4), 1) AS final_grade
                   FROM course_table c
                   JOIN name_table n ON c.student_id = n.student_id
                   WHERE c.student_id LIKE ? OR c.course_code LIKE ?
                   ORDER BY c.student_id";

    $stmt = $conn->prepare($sql_search);
    $stmt->bind_param("ss", $search_term, $course_search_term);
    $stmt->execute();
    $result_courses = $stmt->get_result();
} else {
    $sql_courses = "SELECT c.student_id, n.student_name, c.course_code, 
                    c.test1, c.test2, c.test3, c.final_exam,
                    ROUND((c.test1 * 0.2) + (c.test2 * 0.2) + (c.test3 * 0.2) + (c.final_exam * 0.4), 1) AS final_grade
                    FROM course_table c
                    JOIN name_table n ON c.student_id = n.student_id
                    ORDER BY c.student_id";
    $result_courses = $conn->query($sql_courses);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Dashboard</title>
    <script>
        function validateInput(input) {
            if (input.value < 0 || input.value > 100) {
                alert("Grades must be between 0 and 100!");
                input.value = "";
            }
        }
    </script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        .container { width: 60%; margin: auto; background: white; padding: 20px; border-radius: 10px; 
                     box-shadow: 0px 0px 10px 0px #ccc; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 10px; text-align: center; }
        th { background-color: #ddd; }
        .delete-btn { padding: 5px; background: red; color: white; border: none; cursor: pointer; }
        .search-box { margin-bottom: 10px; }
        .search-btn { padding: 10px; background: blue; color: white; border: none; cursor: pointer; }
        .search-btn:hover { background: darkblue; }
        .update-form { width: 50%; margin: auto; padding: 20px; background: #eee; border-radius: 10px; }
        .update-btn { padding: 10px; background: green; color: white; border: none; cursor: pointer; }
        .update-btn:hover { background: darkgreen; }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome!</h2>
    
    <!-- Search Form -->
    <form method="get">
        <input type="text" name="search" placeholder="Search Student ID or Course Code">
        <button type="submit" class="search-btn">Search</button>
    </form>

    <h3>Student Records</h3>
    
    <table>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Course Code</th>
            <th>Test 1</th>
            <th>Test 2</th>
            <th>Test 3</th>
            <th>Final Exam</th>
            <th>Final Grade</th>
            <th>Delete</th>
        </tr>
        <?php 
        while ($row = $result_courses->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                <td><?php echo $row['test1']; ?></td>
                <td><?php echo $row['test2']; ?></td>
                <td><?php echo $row['test3']; ?></td>
                <td><?php echo $row['final_exam']; ?></td>
                <td><?php echo $row['final_grade']; ?></td>
                <td>
                    <form method="post" action="delete.php">
                        <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                        <input type="hidden" name="course_code" value="<?php echo htmlspecialchars($row['course_code']); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- Update Form (At the Bottom) -->
    <div class="update-form">
        <h3>Update Student Grades</h3>
        <form method="post" action="update.php">
            <label>Student ID:</label>
            <input type="text" name="student_id" required><br><br>
            
            <label>Course Code:</label>
            <input type="text" name="course_code" required><br><br>
            
            <label>Select Grade to Update:</label><br>
            <select name="grade_type" required>
                <option value="test1">Test 1</option>
                <option value="test2">Test 2</option>
                <option value="test3">Test 3</option>
                <option value="final_exam">Final Exam</option>
            </select><br><br>

            <label>New Grade:</label>
            <input type="number" name="new_grade" oninput="validateInput(this)" required><br><br>

            <button type="submit" class="update-btn">Update Grade</button>
        </form>
    </div>

    <br>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
