<?php
session_start();
require_once 'core/dbConfig.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'applicant') {
    header("Location: login.php");
    exit();
}

// Fetching messages
$stmt = $pdo->prepare("SELECT m.id, m.from_user_id, m.to_user_id, m.message, m.created_at, u.username AS sender_username, u2.username AS recipient_username 
                        FROM messages m
                        JOIN users u ON m.from_user_id = u.id
                        LEFT JOIN users u2 ON m.to_user_id = u2.id
                        WHERE m.from_user_id = ? OR m.to_user_id = ?
                        ORDER BY m.created_at DESC");
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$messages = $stmt->fetchAll();

// Handling message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['reply_message']) || isset($_POST['send_message']))) {
    $messageContent = $_POST['message'];
    $hrUserId = $_POST['hr_user_id'];

    $query = "INSERT INTO messages (from_user_id, to_user_id, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['user_id'], $hrUserId, $messageContent]);

    header("Location: applicant_messages.php");
    exit();
}

// Handling message deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $messageId = $_POST['message_id'];

    // Ensure only the message owner can delete it
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ? AND (from_user_id = ? OR to_user_id = ?)");
    $stmt->execute([$messageId, $_SESSION['user_id'], $_SESSION['user_id']]);

    header("Location: applicant_messages.php");
    exit();
}

// Fetching HR users
function getHRUsers() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE role = 'hr'");
    $stmt->execute();
    return $stmt->fetchAll();
}

$hrUsers = getHRUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Messages</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Press+Start+2P');

        body {
            font-family: 'Press Start 2P', cursive;
            background-color:rgb(33, 20, 46);
            background-size: cover;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #2e2e48;
            border: 4px solid #ffcc00;
            border-radius: 8px;
            box-shadow: 0 8px 0 #aa8800, 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        h1, h2 {
            text-align: center;
            color: #ff0055;
            text-shadow: 0 2px 0 #aa0033;
        }

        nav {
            text-align: center;
            margin-bottom: 20px;
        }

        nav a {
            text-decoration: none;
            color: #00ffcc;
            margin: 0 10px;
            font-size: 14px;
        }

        nav a:hover {
            color: #ffcc00;
        }

        form {
            margin-bottom: 30px;
        }

        form label {
            font-size: 14px;
            color: #ffcc00;
        }

        select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ffcc00;
            background-color: #1a1a2e;
            color: #ffffff;
            font-family: 'Press Start 2P', cursive;
        }

        button {
            background-color: #ff0055;
            color: white;
            border: 2px solid #aa0033;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            text-transform: uppercase;
            font-family: 'Press Start 2P', cursive;
        }

        button:hover {
            background-color: #aa0033;
            color: #ffffff;
        }

        .message-history {
            list-style-type: none;
            padding: 0;
        }

        .message-item {
            background-color: #1a1a2e;
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid #ffcc00;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #ffffff;
        }

        .message-item strong {
            color: #00ffcc;
        }

        .message-item p {
            color: #ffffff;
            font-size: 12px;
        }

        .message-item small {
            color: #ffcc00;
        }

        .reply-form textarea {
            margin-top: 10px;
        }

        .no-messages {
            text-align: center;
            color: #ffcc00;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Messages</h1>
        <nav>
            <a href="applicant_dashboard.php">Back to Dashboard</a> |
            <a href="core/handleForms.php?logoutAUser=1">Logout</a>
        </nav>

        <h2>Send Message to HR</h2>
        <form method="POST" action="applicant_messages.php">
            <label for="hr_user_id">Select HR User:</label>
            <select name="hr_user_id" id="hr_user_id" required>
                <?php foreach ($hrUsers as $hr): ?>
                    <option value="<?= htmlspecialchars($hr['id']); ?>"><?= htmlspecialchars($hr['username']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="message">Your Message:</label>
            <textarea name="message" rows="4" required placeholder="Type your message here."></textarea>

            <button type="submit" name="send_message">Send Message</button>
        </form>

        <h2>Message History</h2>
<?php if (!empty($messages)): ?>
    <ul class="message-history">
        <?php foreach ($messages as $message): ?>
            <li class="message-item">
                <?php if ($message['from_user_id'] == $_SESSION['user_id']): ?>
                    <strong>To: <?= htmlspecialchars($message['recipient_username']); ?></strong>
                <?php else: ?>
                    <strong>From: <?= htmlspecialchars($message['sender_username']); ?></strong>
                <?php endif; ?>
                <p><?= nl2br(htmlspecialchars($message['message'])); ?></p>
                <small>Sent/Received on: <?= htmlspecialchars($message['created_at']); ?></small>

                <!-- Reply Form -->
                <?php if ($message['from_user_id'] != $_SESSION['user_id']): ?>
                    <div class="reply-form">
                        <form method="POST" action="applicant_messages.php">
                            <textarea name="message" rows="2" required placeholder="Type your reply here."></textarea>
                            <input type="hidden" name="hr_user_id" value="<?= $message['from_user_id']; ?>">
                            <button type="submit" name="reply_message">Reply</button>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Delete Button -->
                <form method="POST" action="applicant_messages.php" style="margin-top: 10px;">
                    <input type="hidden" name="message_id" value="<?= $message['id']; ?>">
                    <button type="submit" name="delete_message" style="background-color: #ff0055;">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="no-messages">No messages available.</p>
<?php endif; ?>
    </div>
</body>
</html>
