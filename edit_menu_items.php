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

    $delete_sql = "DELETE FROM menu_items WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_sql)) {
        $message = "Menu item deleted successfully.";
    } else {
        $message = "Error deleting menu item: " . mysqli_error($conn);
    }
}

// Fetch all menu items with category names
$sql = "SELECT menu_items.id, categories.name AS category_name, menu_items.name, menu_items.description, menu_items.price 
        FROM menu_items 
        JOIN categories ON menu_items.category_id = categories.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Delete Menu Items</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/menu_items.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="menu_bg">
        <h1>Edit/Delete Menu Items</h1>
    </div>
    <div class="all">
        <?php
        if (isset($message)) {
            echo '<p>' . $message . '</p>';
        }
        ?>
        <table>
            <tr>
                <th>Category</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                            <td>' . $row["category_name"] . '</td>
                            <td>' . $row["name"] . '</td>
                            <td>' . $row["description"] . '</td>
                            <td>GHS ' . $row["price"] . '</td>
                            <td><a href="edit_menu_item.php?id=' . $row["id"] . '"><i class="fa-regular fa-pen-to-square"></i></a></td>
                            <td>
                                <form action="edit_menu_items.php" method="post">
                                    <input type="hidden" name="delete_id" value="' . $row["id"] . '">
                                    <button  class="delete" type="submit" onclick="return confirm(\'Are you sure you want to delete this menu item?\')"><i class="fa-regular fa-trash-can"></i></button>
                                </form>
                            </td>
                          </tr>';
                }
            } else {
                echo '<tr><td colspan="6">No menu items found.</td></tr>';
            }
            ?>
        </table>
        <br>
        <a href="add_item.php">Add New Menu Item</a>
    </div>
</body>
</html>
