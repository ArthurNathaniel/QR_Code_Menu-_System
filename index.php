<?php
include 'db.php';

// Query to fetch menu items grouped by categories
$sql = "SELECT categories.name AS category_name, 
               menu_items.id, 
               menu_items.name, 
               menu_items.description, 
               menu_items.price 
        FROM menu_items 
        JOIN categories ON menu_items.category_id = categories.id 
        ORDER BY categories.name"; // Ensure results are ordered by category

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="./css/base.css">
</head>
<body>
    <div class="menu_bg">
        <h1>MENU</h1>
    </div>
    <div class="menu_grid">
        <?php
        $current_category = null; // Track the current category

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Check if category has changed
                if ($current_category != $row["category_name"]) {
                    // If it has changed, start a new category section
                    if ($current_category !== null) {
                        // Close the previous category section
                        echo '</div></div>';
                    }
                    // Start a new category section
                    echo '<div class="menu_one">
                            <div class="title">
                                <h1>' . $row["category_name"] . '</h1>
                            </div>
                            <div class="category_details">'; // Start category details
                    $current_category = $row["category_name"];
                }

                // Display each menu item under the current category
                echo '<div class="food_details">
                        <div class="food_name">
                            <p>' . $row["name"] . '</p>
                            <span>(' . $row["description"] . ')</span>
                        </div>
                        <div class="dots">
                            <p>....................</p>
                        </div>
                        <div class="food_price">
                            GHS ' . $row["price"] . '
                        </div>
                    </div>';
            }

            // Close the last category section
            echo '</div></div>';
        } else {
            echo "<p>No menu items found</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
