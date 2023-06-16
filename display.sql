DELIMITER //

CREATE PROCEDURE GetFollowedUsersTweets(IN id VARCHAR(255))
BEGIN
    SELECT u.username, t.content, t.timestamp
    FROM users AS u
    JOIN follows AS f ON u.id = f.following_id
    JOIN tweets AS t ON u.id = t.user_id
    WHERE f.follower_id = (SELECT id FROM users WHERE username = username LIMIT 1)
    ORDER BY t.timestamp DESC;
END //

CREATE PROCEDURE GetUserTweets(IN id VARCHAR(255))
BEGIN
    SELECT u.username, t.content, t.timestamp
    FROM users AS u
    JOIN tweets AS t ON u.id = t.user_id
    WHERE u.username = username
    ORDER BY t.timestamp DESC;
END //

DELIMITER ;