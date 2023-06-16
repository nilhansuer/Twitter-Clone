<?php

$host = 'localhost';     
$dbName = 'project'; 
$user = 'root';  
$password = '1234';  


try {
  $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
  $pdo = new PDO($dsn, $user, $password);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, name, password) VALUES (:username, :name, :hashedPassword)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':hashedPassword', $hashedPassword); 
    $stmt->execute();

    header("Location: index.html");
    exit();
  }
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
?>
