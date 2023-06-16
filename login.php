<?php
session_start();

$host = "localhost"; 
$dbUsername = "root"; 
$dbPassword = "1234"; 
$dbName = "project"; 

$connection = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$username = $_POST['username'];
$password = $_POST['password'];

$username = mysqli_real_escape_string($connection, $username);

$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $storedPassword = $row['password'];

    if (password_verify($password, $storedPassword)) {
        $_SESSION['user_id'] = $row['id'];
        header("Location: homepage.php");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

mysqli_close($connection);
?>
