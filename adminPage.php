<?php
session_start();
include "functions.php";

displayNavBar();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Admin Area </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php 

    // Checking if add product button has been pressed
    if(isset($_POST['addProduct'])) {

        // Validating that all fields need to be filled out
        if(!validateArr($_POST)) echo "<script>alert('all fields need to be filled');</script>";

        // Creating new product based on the post request
        $product = new Product($_POST["product"], $_POST["description"], $_POST["price"], $_POST["image"]);
    }

    // Checking if delete product button has been pressed
    if(isset($_POST["deleteBtn"])) {
        $productId = $_POST["product_id"];
        $query = "DELETE FROM products WHERE product_id='$productId'";
        Database::runQuery($query);
    }

    // DONE: Have a table to display current products
    $resArr = Database::readFromTable('products');
    echo '<h2>Products Table</h2>';
    createTable($resArr, 'admin');
    
?>
    <!-- Form for adding products to databse -->
    <form action="" method="POST">
        <h2>Add Product</h2>
        <label for="image">Image: </label>
        <input type="file" name="image" required>
        <br>

        <label for="product">Product name:</label>
        <input type="text" name="product" required>
        <br>

        <label for="description">Description: </label>
        <input type="text" name="description" required>
        <br>

        <label for="price">Price: </label>
        <input type="number" name="price" min="0" required>
        <br>

        <input type="submit" value="Add Product" name="addProduct">
    </form>

    <!-- Form for selecting and deleting products -->
    <form method="POST" action="">
        <h2>Delete product</h2>
        <label for="product_id">Product id:</label>
        <select name="product_id">
            <?php 
                foreach($resArr as $order) {
                    echo "<option value=\"${order['product_id']}\">product no.${order['product_id']}</option>";
                }
            ?>
        </select>
        <input type="submit" value="Delete product" class="submit" name="deleteBtn">
    </form>



    <!-- Table for orders -->
    <?php 
        echo '<h2>Table of current orders</h2>';
        if(isset($_POST["sortBtn"])) {
            print_r($_POST);
            $orders = Database::readFromTable('orders', $_POST["sort"]);
            createTable($orders, 'admin');
        } else {
            $orders = Database::readFromTable('orders');
            createTable($orders, 'admin');
        }

        
    ?>
    
    <!-- Form for selecting what to sort the orders tale by -->
    <form action="" method="POST">
        <label for="sort">Sort By: </label>
        <select name="sort">
            <option value="quantity">Quantity</option>
            <option value="time">Time</option>
            <option value="customer_id">Customer</option>
            <option value="product_id">Product</option>
        </select>
        <input type="submit" value="display orders" name="sortBtn">
    </form>

    
</body>
</html>