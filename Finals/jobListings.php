<?php
session_start();
require_once 'core/models.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'applicant') {
    header("Location: login.php");
    exit();
}

$jobPosts = getAllJobPosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
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

        nav {
            background-color: #2e2e48;
            padding: 15px;
            text-align: center;
            box-shadow: 0 8px 0 #aa8800, 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        nav a {
            color: #ffcc00;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 14px;
            text-shadow: 0 2px 0 #aa8800;
        }

        nav a:hover {
            color: #00ffcc;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #2e2e48;
            border: 4px solid #ffcc00;
            border-radius: 8px;
            box-shadow: 0 8px 0 #aa8800, 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            color: #ff0055;
            text-shadow: 0 2px 0 #aa0033;
            margin-bottom: 30px;
        }

        .job-post {
            background-color: #1a1a2e;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid #ffcc00;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .job-post h2 {
            color: #ffcc00;
            margin-bottom: 10px;
        }

        .job-post p {
            color: #ffffff;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .apply-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .apply-form textarea,
        .apply-form input[type="file"] {
            padding: 10px;
            border: 2px solid #ffcc00;
            border-radius: 4px;
            font-family: 'Press Start 2P', cursive;
            background-color: #1a1a2e;
            color: #ffffff;
        }

        .apply-form button {
            background-color: #ff0055;
            color: white;
            border: 2px solid #aa0033;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            font-family: 'Press Start 2P', cursive;
            transition: background-color 0.3s ease;
        }

        .apply-form button:hover {
            background-color: #aa0033;
            color: #ffffff;
        }

        .no-jobs {
            text-align: center;
            color: #ffcc00;
            font-size: 18px;
        }
    </style>
</head>
<body>
<nav>
    <a href="applicant_dashboard.php">Dashboard</a>
    <a href="myApplications.php">My Applications</a>
    <a href="applicant_messages.php">Messages</a>
    <a href="core/handleForms.php?logoutAUser=1">Logout</a>
</nav>


    <div class="container">
        <h1>Available Job Listings</h1>

        <?php
        if (empty($jobPosts)) {
            echo "<p class='no-jobs'>No job listings available at the moment.</p>";
        } else {
            foreach ($jobPosts as $job) {
                echo "<div class='job-post'>";
                echo "<h2>" . htmlspecialchars($job['title']) . "</h2>";
                echo "<p>" . htmlspecialchars($job['description']) . "</p>";

                echo "<form action='core/handleForms.php' method='POST' enctype='multipart/form-data' class='apply-form'>";
                echo "<input type='hidden' name='job_post_id' value='" . $job['id'] . "'>";
                echo "<textarea name='cover_message' placeholder='Why should we hire you?' required></textarea>";
                echo "<input type='file' name='resume' accept='.pdf' required>";
                echo "<button type='submit' name='applyJobBtn'>Apply</button>";
                echo "</form>";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>
</html>
