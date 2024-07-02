<?php
include 'db.php';

$message = '';

// Function to sanitize input
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($input)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $check_sql = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $message = "Username already exists.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit(); // Ensure no further code is executed after redirection
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="all">
        <div class="one">
            <div class="logo"></div>
  <div class="forms">
  <h2>Signup</h2>
  </div>
        <?php if ($message != '') { ?>
            <div class="message-container">
                <p><?php echo $message; ?></p>
                <i class="fa-regular fa-circle-xmark close-icon" onclick="this.parentElement.style.display='none';"></i>
            </div>
        <?php } ?>
        <form action="signup.php" method="post">
            <div class="forms">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="forms">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="forms">
                <button type="submit">Signup</button>
            </div>
        </form>
        <br>
        <a href="login.php">Already have an account? Login here</a>
        </div>
        
    </div>
</body>
</html>
