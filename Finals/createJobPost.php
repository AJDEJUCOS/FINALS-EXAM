<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: ../login.php");
    exit();
}

require_once 'core/dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    try {
        $sql = "INSERT INTO job_posts (title, description, created_by) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $description, $_SESSION['user_id']]);
        
        echo "Job post created successfully!";
        header("Location: hr_dashboard.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Post</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Press+Start+2P');

        body {
            font-family: 'Press Start 2P', cursive;
            background-color: rgb(33, 20, 46);
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2e2e48;
            color: #ffcc00;
            padding: 15px;
            text-align: center;
            box-shadow: 0 8px 0 #aa8800, 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        header h1 {
            margin: 0;
            font-size: 16px;
            text-shadow: 0 2px 0 #aa8800;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: #2e2e48;
            border: 4px solid #ffcc00;
            border-radius: 8px;
            box-shadow: 0 8px 0 #aa8800, 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            color:rgb(255, 255, 255);
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #ffcc00;
            border-radius: 4px;
            background-color: #1a1a2e;
            color: #ffffff;
            font-size: 14px;
            font-family: 'Press Start 2P', cursive;
            box-shadow: inset 0 4px 0 #aa8800;
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

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            color: #ffcc00;
            font-size: 14px;
        }

        .back-link a:hover {
            color: #00ffcc;
        }
    </style>
</head>
<body>
    <header>
        <h1>Create a New Job Post</h1>
    </header>

    <div class="container">
        <form method="POST" action="createJobPost.php">
            <label for="title">Job Title:</label>
            <input type="text" name="title" required>

            <label for="description">Job Description:</label>
            <textarea name="description" required></textarea>

            <button type="submit">Create Job Post</button>
        </form>

        <div class="back-link">
            <a href="hr_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
