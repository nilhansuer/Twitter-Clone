<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

if (empty($_POST['tweet_content'])) {
    header('Location: profile.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "project";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO tweets (user_id, content) VALUES (:user_id, :content)");
    $stmt->bindValue(':user_id', $_SESSION['user_id']);
    $stmt->bindValue(':content', $_POST['tweet_content']);
    $stmt->execute();

    header('Location: profile.php');
    exit();
} catch(PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}
?>
