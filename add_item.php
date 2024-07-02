<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_name'])) {
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Check for duplicate item
    $check_sql = "SELECT * FROM menu_items WHERE category_id = '$category_id' AND name = '$name'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $message = "Menu item already exists.";
    } else {
        $sql = "INSERT INTO menu_items (category_id, name, description, price) VALUES ('$category_id', '$name', '$description', '$price')";

        if (mysqli_query($conn, $sql)) {
            $message = "New menu item created successfully";
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
    <title>Add Menu Item</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/menu_items.css">   
</head>

<body>
<?php include 'sidebar.php'; ?>
    <div class="menu_bg">
        <h1>ADD MENU FOOD</h1>
    </div>
    <div class="menu_list_all">
        <?php if ($message != '') { ?>
            <div class="message-container">
                <p><?php echo $message; ?></p>
                <i class="fa-regular fa-circle-xmark close-icon" onclick="this.parentElement.style.display='none';"></i>
            </div>
        <?php } ?>
        <form action="add_item.php" method="post">
            <div class="forms">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" required>
                    <?php
                    $sql = "SELECT * FROM categories";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="forms">
                <label for="item_name">Food Name:</label>
                <input type="text" id="item_name" name="item_name" required>
            </div>
            <div class="forms">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" required>
            </div>
            <div class="forms">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>
            </div>
            <div class="forms">
                <button type="submit">Add Menu Item</button>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>
