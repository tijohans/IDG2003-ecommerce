<?php
session_start();
include "functions.php";

displayNavBar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Product Page </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Product Page</h2>
    <?php 
    
    // Display specific information about product selected in the previous page. 
    // Note that the product page can only be accessed from the main page. 
    // Add a form : with a select field to choose quantity, and a submit button named "Add to cart", which will populate the shopping cart. 
    // Shopping cart information can be preserved in a cookie. If the user closes the browser and reopens the page, the shopping cart information can be repopulated from the cookie. 
    // Modify the shopping cart link in the navigation bar when an item is added to it. 
    $data = Database::readFromTable('products');
    createTable($data, 'product');

    
    
    
    if(isset($_POST["addToCartBtn"])) {
        $itemData = array('id' => $_GET["id"], 'quantity' => $_POST["quantity"]);

        $cart = new Cart;
        // Validating that all fields are filled out
        echo validateArr($itemData) ? $cart->addToCart($itemData) : 'All fields need to be filled out';
    }



    ?>
    
    <form action="" method="POST">
        <label for="quantity">Choose amount: </label>
        <input type="number" name="quantity">

        <input type="submit" value="Add to cart" name="addToCartBtn">
    </form>
    
    
    
</body>
</html>
    