<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Function to sanitize input
function sanitize($input)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($input)));
}

// Initialize variables
$message = '';
$name = '';
$description = '';
$price = '';

// Fetch menu item details based on ID from URL parameter
if (isset($_GET['id'])) {
    $id = sanitize($_GET['id']);

    // Query to fetch menu item details
    $select_sql = "SELECT * FROM menu_items WHERE id = $id";
    $result = mysqli_query($conn, $select_sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
    } else {
        $message = "Menu item not found.";
    }
} else {
    $message = "Menu item ID not specified.";
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = sanitize($_POST['id']);
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = sanitize($_POST['price']);

    // Update query
    $update_sql = "UPDATE menu_items SET name = '$name', description = '$description', price = '$price' WHERE id = $id";

    if (mysqli_query($conn, $update_sql)) {
        $message = "Update successful.";
    } else {
        $message = "Error updating menu item: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/menu_items.css">
</head>

<body>
<?php include 'sidebar.php'; ?>
    <div class="menu_bg">
        <h1>Edit Menu Item</h1>
    </div>
    <div class="all">
    <?php if ($message != '') { ?>
            <div class="message-container">
                <p><?php echo $message; ?></p>
                <i class="fa-regular fa-circle-xmark close-icon" onclick="this.parentElement.style.display='none';"></i>
            </div>
        <?php } ?>
        <form action="edit_menu_item.php?id=<?php echo $id; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="forms">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="forms">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($description); ?>" required>
            </div>
            <div class="forms">
                <label for="price">Price (GHS):</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" min="0" step="any" required>
            </div>
            <div class="forms">
                <button type="submit">Update</button>
            </div>
        </form>
        
        <a href="edit_menu_items.php">Back to Menu Items</a>
    </div>
</body>

</html>
