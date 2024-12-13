<?php
session_start();
require_once 'core/models.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hr') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['jobId'])) {
    $jobId = intval($_GET['jobId']);
    $stmt = $pdo->prepare("SELECT * FROM job_posts WHERE id = ?");
    $stmt->execute([$jobId]);
    $job = $stmt->fetch();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];

        $updateStmt = $pdo->prepare("UPDATE job_posts SET title = ?, description = ? WHERE id = ?");
        $updateStmt->execute([$title, $description, $jobId]);

        header("Location: hrDashboard.php");
        exit();
    }
} else {
    header("Location: hrDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Post</title>
</head>
<body>
    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($job['title']); ?>" required>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($job['description']); ?></textarea>
        
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>