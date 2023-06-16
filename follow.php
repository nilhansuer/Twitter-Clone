<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

if (empty($_POST['search_username'])) {
    echo "Search username is required.";
    exit();
}

$searchUsername = $_POST['search_username'];

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "project";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :search_username");
    $stmt->bindValue(':search_username', $searchUsername);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO follows (follower_id, following_id) VALUES (:follower_id, :following_id)");
    $stmt->bindValue(':follower_id', $_SESSION['user_id']);
    $stmt->bindValue(':following_id', $user['id']);
    $stmt->execute();

    echo "User followed successfully.";
} catch(PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}
?>