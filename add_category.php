<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);

    // Check if the category already exists
    $check_sql = "SELECT * FROM categories WHERE name = '$name'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $message = "Category already exists.";
    } else {
        $sql = "INSERT INTO categories (name) VALUES ('$name')";

        if (mysqli_query($conn, $sql)) {
            $message = "New category created successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    mysqli_close($conn); // Close the connection after the operation
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/category.css">
    
</head>

<body>
<?php include 'sidebar.php'; ?>
    <div class="menu_bg">
        <h1>ADD CATEGORY</h1>
    </div>
    <div class="category_all">
        <?php if ($message != '') { ?>
            <div class="message-container">
                <p><?php echo $message; ?></p>
                <i class="fa-regular fa-circle-xmark close-icon" onclick="this.parentElement.style.display='none';"></i>
            </div>
        <?php } ?>
        <form action="add_category.php" method="post">
            <div class="forms">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" required>
            </div>
            <div class="forms">
                <button type="submit">
                    Add Category
                </button>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>
