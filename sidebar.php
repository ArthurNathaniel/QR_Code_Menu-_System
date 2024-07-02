<style>
    .side-logo {
        text-align: center;
    }
</style>
<div class="sidebar_all">

<div class="logo"></div>
  
    <div class="links">
    <h3> <span class="icon"><i class="fa-solid fa-bookmark"></i></span> MENU DASHBOARD</h3>
      
    <a href="menu.php">Main Menu</a>
      <a href="add_category.php">Add Category</a>
      <a href="add_item.php">Add Menu Item(Food)</a>
      <a href="edit_categories.php">Edit Category</a>
      <a href="edit_menu_items.php">Edit Menu Item(Food)</a>
      <a href="logout.php" class="logout" >
            <i class="fas fa-sign-out-alt"></i> LOGOUT
           
        </a>
    </div>
    <style>
        h3 a {
            background-color: transparent;
        }
    </style>
</div>
<button id="toggleButton">
    <i class="fa-solid fa-bars-staggered"></i>
</button>

<script>
    // Get the button and sidebar elements
    var toggleButton = document.getElementById("toggleButton");
    var sidebar = document.querySelector(".sidebar_all");
    var icon = toggleButton.querySelector("i");

    // Add click event listener to the button
    toggleButton.addEventListener("click", function() {
        // Toggle the visibility of the sidebar
        if (sidebar.style.display === "none" || sidebar.style.display === "") {
            sidebar.style.display = "block";
            icon.classList.remove("fa-bars-staggered");
            icon.classList.add("fa-xmark");
        } else {
            sidebar.style.display = "none";
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars-staggered");
        }
    });
</script>