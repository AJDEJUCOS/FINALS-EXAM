<?php
session_start();
require_once 'core/models.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['deleteJobId'])) {
    $jobId = intval($_GET['deleteJobId']);
    try {
        // Delete associated applications
        $queryApplications = "DELETE FROM applications WHERE job_post_id = ?";
        $stmtApplications = $pdo->prepare($queryApplications);
        $stmtApplications->execute([$jobId]);

        // Delete the job post
        $queryJobPost = "DELETE FROM job_posts WHERE id = ?";
        $stmtJobPost = $pdo->prepare($queryJobPost);
        $stmtJobPost->execute([$jobId]);

        header("Location: hrDashboard.php");
exit(); // Redirect to refresh the page
        exit();
    } catch (PDOException $e) {
        echo "<p>Error deleting job post: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Handle edit job
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editJob'])) {
        $jobId = intval($_POST['jobId']);
        $title = $_POST['title'];
        $description = $_POST['description'];

        try {
            $query = "UPDATE job_posts SET title = ?, description = ? WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$title, $description, $jobId]);
            header("Location: hr_dashboard.php"); // Refresh page
            exit();
        } catch (PDOException $e) {
            echo "<p>Error editing job post: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    // Handle delete job
    if (isset($_POST['deleteJobId'])) {
        $jobId = intval($_POST['deleteJobId']);

        try {
            $query = "DELETE FROM job_posts WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$jobId]);
            header("Location: hr_dashboard.php"); // Refresh page
            exit();
        } catch (PDOException $e) {
            echo "<p>Error deleting job post: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
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
            padding: 20px;
            text-align: center;
            box-shadow: 0 8px 0 #aa8800, 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        header h1 {
            margin: 0;
            font-size: 16px;
            text-shadow: 0 2px 0 #aa8800;
        }

        nav {
            text-align: center;
            padding: 15px 0;
            background-color: #1a1a2e;
            box-shadow: 0 8px 0 #aa8800, 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        nav a {
            color: #ffcc00;
            margin: 0 15px;
            text-decoration: none;
            font-size: 14px;
        }

        nav a.logout-link {
            color: #ffcc00;
        }

        nav a:hover {
            color: #00ffcc;
        }

        .container {
            width: 90%;
            margin: 30px auto;
            padding: 20px;
            background-color: #2e2e48;
            border: 4px solid #ffcc00;
            border-radius: 8px;
            box-shadow: 0 8px 0 #aa8800, 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: #ff0055;
            margin-bottom: 20px;
            text-shadow: 0 2px 0 #aa0033;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            background-color: #1a1a2e;
            margin: 8px 0;
            padding: 10px;
            border: 2px solid #ffcc00;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        ul li strong {
            color: #ffcc00;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .action-buttons a, .action-buttons button {
            text-decoration: none;
            background-color: #ff0055;
            color: #ffffff;
            padding: 5px 10px;
            font-size: 12px;
            font-family: 'Press Start 2P', cursive;
            border-radius: 4px;
            box-shadow: 0 4px 0 #aa0033;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .action-buttons a:hover, .action-buttons button:hover {
            background-color: #aa0033;
        }

        .delete-link {
            background-color: #ff4444;
            box-shadow: 0 4px 0 #aa2222;
        }

        .delete-link:hover {
            background-color: #aa2222;
        }

        .edit-form {
            margin-top: 10px;
        }

        .edit-form input, .edit-form textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 14px;
            border: 2px solid #ffcc00;
            font-family: 'Press Start 2P', cursive;
            border-radius: 4px;
            background-color: #1a1a2e;
            color: #ffffff;
        }

        .edit-form textarea {
            resize: vertical;
        }

        .edit-form button {
            background-color: #00cc66;
            font-family: 'Press Start 2P', cursive;color: #ffffff;
            padding: 5px 10px;
            font-size: 12px;
            font-family: 'Press Start 2P', cursive;
            border-radius: 4px;
            box-shadow: 0 4px 0 #008844;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .edit-form button:hover {
            background-color: #008844;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome HR: <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    </header>

    <nav>
        <a href="createJobPost.php">Create Job Post</a>
        <a href="viewApplications.php">View Applications</a>
        <a href="messages.php">Messages</a>
        <a href="core/handleForms.php?logoutAUser=1" class="logout-link">Logout</a>
    </nav>

    <div class="container">
    <h2>Your Job Posts</h2>
    <?php
    $jobPosts = getJobPosts($user_id);
    if (!empty($jobPosts)) {
        echo "<ul>";
        foreach ($jobPosts as $job) {
            echo "<li>
                <strong>" . htmlspecialchars($job['title']) . "</strong> - " . htmlspecialchars($job['description']) . "
                <div class='action-buttons'>
                    <button onclick=\"document.getElementById('editForm" . $job['id'] . "').style.display='block';\">Edit</button>
                    <form method='POST' action='' style='display:inline;'>
                        <input type='hidden' name='deleteJobId' value='" . htmlspecialchars($job['id']) . "'>
                        <button type='submit' class='delete-link'>Delete</button>
                    </form>
                </div>
                <form id='editForm" . $job['id'] . "' class='edit-form' style='display:none;' method='POST'>
                    <input type='hidden' name='jobId' value='" . htmlspecialchars($job['id']) . "'>
                    <input type='text' name='title' value='" . htmlspecialchars($job['title']) . "' placeholder='Job Title' required>
                    <textarea name='description' placeholder='Job Description' required>" . htmlspecialchars($job['description']) . "</textarea>
                    <button type='submit' name='editJob'>Save Changes</button>
                </form>
            </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No job posts available.</p>";
    }
    ?>
</div>

</body>
</html>
