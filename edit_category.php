<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$message = '';

// Function to sanitize input
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($input)));
}

// Check if ID is provided in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $message = "Category ID not provided.";
} else {
    $category_id = sanitize($_GET['id']);

    // Fetch current category details
    $sql = "SELECT * FROM categories WHERE id = $category_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $category_name = $row['name'];

        // Handling form submission for updating category name
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
            $new_name = sanitize($_POST['category_name']);

            // Check for duplicate category name
            $check_sql = "SELECT * FROM categories WHERE name = '$new_name' AND id != $category_id";
            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                $message = "Category name already exists.";
            } else {
                // Update category name in database
                $update_sql = "UPDATE categories SET name = '$new_name' WHERE id = $category_id";

                if (mysqli_query($conn, $update_sql)) {
                    $message = "Category updated successfully.";
                    $category_name = $new_name; // Update displayed category name
                } else {
                    $message = "Error updating category: " . mysqli_error($conn);
                }
            }
        }
    } else {
        $message = "Category not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/category.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="menu_bg">
        <h1>Edit CATEGORY</h1>
    </div>
    <div class="all">
      
        <?php if ($message != '') { ?>
            <div class="message-container">
                <p><?php echo $message; ?></p>
                <i class="fa-regular fa-circle-xmark close-icon" onclick="this.parentElement.style.display='none';"></i>
            </div>
        <?php } ?>
        <form action="edit_category.php?id=<?php echo $category_id; ?>" method="post">
            <div class="forms">
            <label for="category_name">Category Name:</label>
            <input type="text" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category_name); ?>" required>
            </div>
       
            <div class="forms">
         
            <button type="submit">Update Category</button>
        </div>
        </form>
        <br>
        <a href="edit_categories.php">Back to Categories</a>
    </div>
</body>
</html>
