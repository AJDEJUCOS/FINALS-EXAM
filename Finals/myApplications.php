<?php
session_start();
require_once 'core/models.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'applicant') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$applications = getApplicationsByApplicant($user_id); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
    <style>
        body {
            font-family: 'Press Start 2P', cursive;
            background-color: rgb(33, 20, 46);
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

        h2 {
            color: #ffcc00;
            text-shadow: 0 2px 0 #aa8800;
            margin-bottom: 20px;
        }

        .applications-list {
            list-style: none;
            padding: 0;
        }

        .application-item {
            background-color: #1a1a2e;
            border: 2px solid #ffcc00;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .application-item h3 {
            color: #ffcc00;
            margin-bottom: 10px;
        }

        .application-item p {
            color: #ffffff;
            font-size: 14px;
        }

        .application-item a {
            color: #00ffcc;
            text-decoration: none;
        }

        .application-item a:hover {
            color: #ff0055;
        }

        .no-applications {
            text-align: center;
            color: #ffcc00;
            font-size: 18px;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            padding: 10px 0;
            background-color: #2e2e48;
            color: #ffcc00;
            text-shadow: 0 2px 0 #aa8800;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="jobListings.php">Job Listings</a>
        <a href="applicant_dashboard.php">Dashboard</a>
        <a href="core/handleForms.php?logoutAUser=1">Logout</a>
    </nav>

    <div class="container">
        <h1>My Applications</h1>
        <h2>Your Job Applications</h2>
        <?php
        if (!empty($applications)) {
            echo "<ul class='applications-list'>";
            foreach ($applications as $application) {
                echo "<li class='application-item'>";
                echo "<h3>" . htmlspecialchars($application['title']) . "</h3>";
                echo "<p><strong>Status:</strong> " . htmlspecialchars($application['status']) . "</p>";
                echo "<p><strong>Cover Message:</strong> " . nl2br(htmlspecialchars($application['cover_message'])) . "</p>";
                echo "<p><strong>Resume:</strong> <a href='uploads/resumes/" . htmlspecialchars(basename($application['resume'])) . "' target='_blank'>View Resume</a></p>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='no-applications'>You haven't applied to any jobs yet.</p>";
        }
        ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Job Portal. All Rights Reserved.
    </footer>
</body>
</html>
