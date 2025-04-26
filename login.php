<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $password = $_POST['password']; 

    // Validate against MySQL credentials
    if ($username === "root" && $password === "") {  // your MySQL username and password
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid MySQL username or password!'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .login-container { width: 300px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; }
        input { margin: 10px; padding: 8px; width: 90%; }
        .login-btn { padding: 10px; background: green; color: white; border: none; cursor: pointer; }
        .login-btn:hover { background: darkgreen; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Enter MySQL Username" required><br>
        <input type="password" name="password" placeholder="Enter MySQL Password" required><br>
        <button type="submit" class="login-btn">Login</button>
    </form>
</div>

</body>
</html>
