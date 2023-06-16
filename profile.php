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

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindValue(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
	
	
	$stmt = $conn->prepare("SELECT COUNT(*) as count FROM tweets WHERE user_id = :user_id");
	$stmt->bindValue(':user_id', $_SESSION['user_id']);
	$stmt->execute();
	$tweetCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM follows WHERE following_id = :user_id");
    $stmt->bindValue(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $followerCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM follows WHERE follower_id = :user_id");
    $stmt->bindValue(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $followingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $stmt = $conn->prepare("CALL GetUserTweets(:user_id)");
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
    <title>Profile</title>
</head>
<body>
    <h1>Welcome, <?php echo $user['name']; ?>!</h1>

    <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
	<p><strong>Tweets:</strong> <?php echo $tweetCount; ?></p>
    <p><strong>Followers:</strong> <?php echo $followerCount; ?></p>
    <p><strong>Following:</strong> <?php echo $followingCount; ?></p>
	<p><strong>Creation Date:</strong> <?php echo $user['creation_date']; ?></p>
	
    <form action="tweet.php" method="POST">
        <textarea name="tweet_content" placeholder="Write a tweet"></textarea><br>
        <button type="submit">Tweet</button>
    </form>

    <h2>Your Tweets:</h2>
    <ul>
        <?php foreach ($tweets as $tweet): ?>
            <li>
                <strong>Content:</strong> <?php echo $tweet['content']; ?><br>
                <strong>Created At:</strong> <?php echo $tweet['timestamp']; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="homepage.php">Homepage</a>
    <a href="logout.php">Logout</a>
</body>
</html>
