<?php
include 'db.php';
session_start();

$message = '';

// Function to sanitize input
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($input)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location:menu.php"); // Redirect to a secure page
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="all">
        <div class="one">
            <div class="logo"></div>
          <div class="forms">
          <h2>Login</h2>
          </div>
        <?php if ($message != '') { ?>
            <div class="message-container">
                <p><?php echo $message; ?></p>
                <i class="fa-regular fa-circle-xmark close-icon" onclick="this.parentElement.style.display='none';"></i>
            </div>
        <?php } ?>
        <form action="login.php" method="post">
            <div class="forms">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="forms">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="forms">
                <button type="submit">Login</button>
            </div>
        </form>
        <br>
        <!-- <a href="signup.php">Don't have an account? Signup here</a> -->
        </div>
    </div>
</body>
</html>
