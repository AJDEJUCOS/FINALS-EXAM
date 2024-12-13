<?php
session_start();
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Redirect unauthorized roles
if ($role !== 'hr' && $role !== 'applicant') {
    header("Location: unauthorized.php");
    exit();
}

// After successful login
session_start();
// Set session variables here (e.g., user_id, username, role)
// Redirect to index.php
header("Location: index.php");
exit();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindHire - Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Press+Start+2P');

        /* General Body Styling */
        body {
            font-family: 'Press Start 2P', cursive;
            background-color: rgb(33, 20, 46);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #ffffff;
        }

        /* Container */
        .dashboard-container {
            background-color: #2e2e48cd;
            border: 4px solid #ff0055;
            box-shadow: 0 8px 0 #aa0033, 0 8px 20px rgba(0, 0, 0, 0.5);
            padding: 20px;
            max-width: 600px;
            text-align: center;
            width: 90%;
        }

        h1, h2 {
            color: #d0ff00;
            text-shadow: 0 2px 0 #aa8800;
        }

        .welcome-message {
            font-size: 14px;
            color: #00ffcc;
            margin-bottom: 20px;
        }

        a {
            display: block;
            font-size: 12px;
            color: #ffffff;
            text-decoration: none;
            padding: 15px;
            margin: 10px 0;
            background-color: #1a1a2e;
            border: 2px solid #ffcc00;
            text-shadow: 0 2px 0 #000;
            transition: all 0.2s ease-in-out;
        }

        a:hover {
            background-color: #ffcc00;
            color: #000;
        }

        .logout-link {
            color: red;
            background-color: transparent;
            border: 2px solid red;
        }

        .logout-link:hover {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="welcome-message">Role: <strong><?php echo htmlspecialchars($role); ?></strong></p>

        <?php if ($role === 'hr'): ?>
            <a href="createJobPost.php">Create Job Post</a>
            <a href="viewApplications.php">View Applications</a>
        <?php elseif ($role === 'applicant'): ?>
            <a href="jobListings.php">View Job Listings</a>
            <a href="myApplications.php">My Applications</a>
        <?php endif; ?>

        <a href="messages.php">Messages</a>
        <a href="core/handleForms.php?logoutAUser=1" class="logout-link">Logout</a>
    </div>
</body>
</html>
