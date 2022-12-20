<?php
session_start();
include "functions.php";
require_once './classes/class_Customer.php';

displayNavBar();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Shopping Cart </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php

    // Add a button for "Pay", when clicked, a form should appear for the customer to fill in his details, 
    // with a final button named "confirm Pay", which adds the order onto the database. 
    // After the order has been added to the database, cookie for the shopping cart should be destroyed. 

    // DONE:  Add a form  to create a new product
    if (isset($_POST['checkoutBtn'])) {
        // Validating that all fields need to be filled out
        // if(!validateArr($_POST)) echo "<script>alert('all fields need to be filled');</script>";
        if (!validateArr($_POST)) echo 'all fields need to be filled out';

        // Creating new product based on the post request
        $customer = new Customer(
            $_POST["firstname"],
            $_POST["lastname"],
            $_POST["address"],
            $_POST["country"]
        );

        $customerId = $customer->getCustomerId();
        User::addCustomerIdToUser($_SESSION["username"], $customerId);


        // Creates an order on the customer
        Order::createOrder($customerId);
        echo 'Order Registered! <br>';
    }

    if (isset($_POST["checkoutBtnWithId"])) {
        if (!validateArr($_POST)) echo 'all fields need to be filled out';

        // Creating order based on customer id
        Order::createOrder($_SESSION["customer_id"]);
        echo 'Order Registered! <br>';
    }

    // Checks it the 'payBtn' from the shoppingCarPage function is pressed
    if (isset($_POST["payBtn"])) {

        // Form to display if the user is not logged in
        if (!$_SESSION["isLoggedIn"]) {
            echo 'Not registered? Create a user <a href="./register.php">here</a> <br>';
            echo 'Already have a user? Log in <a href="./login.php">here</a> <br>';

            echo '
            <form action="" method="POST">
            <h3>Order as customer</h3>
            <label for="firstname">firstname: </label>
            <input type="text" name="firstname">

            <label for="lastname">lastname: </label>
            <input type="text" name="lastname">

            <label for="address">address: </label>
            <input type="text" name="address">

            <label for="country">country: </label>
            <input type="text" name="country">

            <input type="submit" value="Confirm Pay" name="checkoutBtn">
        </form>
        ';
        }

        // Form to display it the user id logged in and already has a customer id
        if (isset($_SESSION["isLoggedIn"]) && isset($_SESSION["customer_id"])) {
            echo 'Logged in as: ' . $_SESSION["username"] . '. <br> Customer id: ' . $_SESSION["customer_id"];
            echo '
            <form method="POST">
                <input type="submit" value="Confirm Pay" name="checkoutBtnWithId">
            </form>';
        }

        // If the user is logged in but does not have a customer_id
        if (isset($_SESSION["isLoggedIn"]) && !isset($_SESSION["customer_id"])) {
            echo 'Logged in as: ' . $_SESSION["username"];
            echo '
        <form action="" method="POST">
            <label for="firstname">firstname: </label>
            <input type="text" name="firstname">

            <label for="lastname">lastname: </label>
            <input type="text" name="lastname">

            <label for="address">address: </label>
            <input type="text" name="address">

            <label for="country">country: </label>
            <input type="text" name="country">

            <input type="submit" value="Confirm Pay" name="checkoutBtn">
        </form>
        ';
        }
    } else {
        // Displays the current items in the shopping cart. 
        shoppingCartPage();
    }


    /* 
        Removing items from cart
    */
    // Gets the data from the json file, to create select options from
    $json_data = json_decode(file_get_contents('ShoppingCart.json'), true);

    // Removing items from shopping cart
    if (isset($_POST["removeBtn"])) {
        // Removing value index of the key value pair
        unset($json_data[$_POST["product_id"]]);

        // Rewriting the json file
        file_put_contents('ShoppingCart.json', json_encode($json_data));

        // Reloading the page so the table of the items in cart refreshes
        header('location: shoppingCart.php');
    }

    ?>


</body>

</html>