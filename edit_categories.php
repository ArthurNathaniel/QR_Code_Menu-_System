<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Function to sanitize input
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($input)));
}

// Handling delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = sanitize($_POST['delete_id']);

    // Check if there are menu items associated with this category
    $check_sql = "SELECT * FROM menu_items WHERE category_id = $delete_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // Cannot delete category with associated menu items
        $message = "Cannot delete category. There are menu items associated with this category.";
    } else {
        // Proceed with deletion
        $delete_sql = "DELETE FROM categories WHERE id = $delete_id";
        if (mysqli_query($conn, $delete_sql)) {
            $message = "Category deleted successfully.";
        } else {
            $message = "Error deleting category: " . mysqli_error($conn);
        }
    }
}

// Fetch all categories
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Delete Categories</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/category.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="menu_bg">
    <h1>Edit/Delete Categories</h1>
</div>
<div class="all">
    <?php
    if (isset($message)) {
        echo '<p>' . $message . '</p>';
    }
    ?>
    <table>
        <tr>
            <th>Category Name</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . $row["name"] . '</td>
                        <td><a href="edit_category.php?id=' . $row["id"] . '"><i class="fa-regular fa-pen-to-square"></i></a></td>
                        <td>
                            <form action="edit_categories.php" method="post">
                                <input type="hidden" name="delete_id" value="' . $row["id"] . '">
                                <button class="delete" type="submit" onclick="return confirm(\'Are you sure you want to delete this category?\')"><i class="fa-regular fa-trash-can"></i></button>
                            </form>
                        </td>
                      </tr>';
            }
        } else {
            echo '<tr><td colspan="3">No categories found.</td></tr>';
        }
        ?>
    </table>
    <br>
    <a href="add_category.php">Add New Category</a>
</div>
</body>
</html>
