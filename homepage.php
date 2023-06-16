<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "project";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("CALL GetFollowedUsersTweets(:user_id)");
    $stmt->bindValue(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
</head>
<body>
	
    <form action="follow.php" method="POST">
        <input type="text" name="search_username" placeholder="Search users">
        <button type="submit">Search</button>
    </form>

    <h2>Tweets from the Users You Follow:</h2>
    <ul>
        <?php foreach ($tweets as $tweet): ?>
            <li>
                <strong>Username:</strong> <?php echo $tweet['username']; ?><br>
                <strong>Content:</strong> <?php echo $tweet['content']; ?><br>
                <strong>Created At:</strong> <?php echo $tweet['timestamp']; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</body>
</html>
