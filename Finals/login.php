<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FindHire</title>
    <link rel="stylesheet" href="styles/style.css?v=1.0">
    <style>
        /* Body Styling */
        body {
        font-family: 'Press Start 2P', cursive; /* Pixelated font (Google Fonts: Press Start 2P) */
        background-color: #1a1a2e; /* Dark retro background */
        background-image: url('https://twistedsifter.com/wp-content/uploads/2013/05/animated-gifs-of-fighting-game-backgrounds-5.gif'); /* Add your pixelated game background */
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        color: #ffffff; /* Bright text for contrast */
        }
    </style>




</head>
<body>
<div class="wrapper">
<div class="login-container">
    <img src="images/logo.png" alt="FindHire Logo" class="logo">
    <h1>WELCOME</h1>
    <h2>sign in to your account</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <p class="error-message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>
    <form method="POST" action="core/handleForms.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" name="loginUserBtn">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</div>
</div>
</body>

</html>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const containers = document.querySelectorAll(".login-container, .container");

    let maxHeight = 0;
    containers.forEach(container => {
      maxHeight = Math.max(maxHeight, container.offsetHeight);
    });

    containers.forEach(container => {
      container.style.height = Math.min(maxHeight, 400) + "px"; // Cap height at 400px
    });
  });
</script>
