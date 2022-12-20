<?php

require_once './classes/class_Product.php';
require_once './classes/class_User.php';
require_once './classes/class_Order.php';
require_once './classes/class_Cart.php';


/* 
    Function for displaying the navbar
*/
function displayNavBar()
{

    // Checks if the cart cookie has been set, 
    // if so gets the number of items in cart
    if (isset($_COOKIE["cart"])) {
        $numberOfItems = Cart::getNumberOfItemsInCart();
    }

    echo "<nav>";

    echo "<a href='mainPage.php'>Home</a>  ";

    // Checking if there are any items in the cart, 
    // if so displays the number of items in cart in the navigation
    echo $numberOfItems ? "<a href='shoppingCart.php'>Shopping Cart 🛒$numberOfItems </a>" : "<a href='shoppingCart.php'>Shopping Cart</a>";

    // Checking if the user is logged in, and if the role of the user is 0
    // it both are true, then show link to admin page
    if ($_SESSION["isLoggedIn"] && $_SESSION["role"] == 0) {
        echo "<a href='adminPage.php'>Admin Page</a>  ";
    }

    // Checks if the user is logged in or not
    // then displays either login or logout
    $loginOrLogout = 'Login';
    if ($_SESSION["isLoggedIn"]) $loginOrLogout = 'Logout';
    echo "<a href='login.php' class='login'>$loginOrLogout</a>  ";

    echo "</nav>";
    echo "<br><br>";
}

/* 
    Function for creating tables
    Takes in an array to create table from
    Optionally takes an argument of which type of table to display
        Different pages have different needs on what to show to the user
        main is the default, which shows the least information
        admin is the least strict, which shows everything
*/
function createTable($resArray, $type = 'main')
{
    // Regular table to display
    if ($type == 'main') {

        $isFirstRow = FALSE;
        echo "<table class='content-table'>";

        foreach ($resArray as $item) {
            if ($isFirstRow == FALSE) {
                // first print headers
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($key == 'product_name' || $key == 'image_name') {
                        echo "<th> $key </th>";
                    }
                }
                echo "</tr>";

                //then print first row of values
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($key == 'image_name') {
                        echo '<td><img class="tableImg" src="./data/' . $value . '"></td>';
                        continue;
                    }

                    if ($key == 'product_name') {
                        echo "<td><a href='./productPage.php?id=" . $item['product_id'] . "'>$value</a></td>";
                    }
                }
                echo "</tr>";

                $isFirstRow = TRUE;
            } else {
                // then print every subsequent row of values
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($key == 'image_name') {
                        echo '<td><img class="tableImg" src="./data/' . $value . '"></td>';
                        continue;
                    }

                    if ($key == 'product_name') {
                        echo "<td><a href='./productPage.php?id=" . $item['product_id'] . "'>$value</a></td>";
                    }
                }
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    // Table to display for product page
    if ($type == 'product') {
        $isFirstRow = FALSE;
        echo "<table class='content-table'>";

        foreach ($resArray as $item) {
            if ($isFirstRow == FALSE) {
                // first print headers
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($key == 'product_id') continue;
                    echo "<th> $key </th>";
                }
                echo "</tr>";

                //then print first row of values
                echo "<tr>";
                foreach ($item as $key => $value) {


                    if ($item['product_id'] == $_GET["id"]) {
                        if ($key == 'product_id') continue;

                        if ($key == 'image_name') {
                            echo '<td><img class="tableImg" src="./data/' . $value . '"></td>';
                            continue;
                        }

                        echo "<td> $value </td>";
                    }
                }
                echo "</tr>";

                $isFirstRow = TRUE;
            } else {
                // then print every subsequent row of values
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($item['product_id'] == $_GET["id"]) {
                        if ($key == 'product_id') continue;

                        if ($key == 'image_name') {
                            echo '<td><img class="tableImg" src="./data/' . $value . '"></td>';
                            continue;
                        }

                        echo "<td> $value </td>";
                    }
                }
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    // Table to display if user is admin
    if ($type == 'admin') {
        $isFirstRow = FALSE;
        echo "<table class='content-table'>";

        foreach ($resArray as $item) {
            if ($isFirstRow == FALSE) {
                // first print headers
                echo "<tr>";
                foreach ($item as $key => $value) {
                    echo "<th> $key </th>";
                }
                echo "</tr>";

                //then print first row of values
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($key == 'image_name') {
                        echo '<td><img class="tableImg" src="./data/' . $value . '"></td>';
                        continue;
                    }

                    echo "<td> $value </td>";
                }
                echo "</tr>";

                $isFirstRow = TRUE;
            } else {
                // then print every subsequent row of values
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($key == 'image_name') {
                        echo '<td><img class="tableImg" src="./data/' . $value . '"></td>';
                        continue;
                    }

                    echo "<td> $value </td>";
                }
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    // Table to display if user is admin
    if ($type == 'cart') {

        // Variable for storing the total cost of the whole order
        $totalCost = 0;

        $isFirstRow = FALSE;
        echo "<table class='content-table'>";

        foreach ($resArray as $item) {
            if ($isFirstRow == FALSE) {
                // first print headers
                echo "<tr>";
                foreach ($item as $key => $value) {
                    echo "<th> $key </th>";
                }
                echo "</tr>";

                //then print first row of values
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($key == 'image_name') {
                        echo '<td><img class="tableImg" src="./data/' . $value . '"></td>';
                        continue;
                    }

                    if ($key == 'price') {
                        // Calculates the total price for the product
                        $price = $value * $item['quantity'];
                        echo '<td>' . $price . '</td>';

                        // Adds the price of the product to the total cost
                        $totalCost += $price;
                        continue;
                    }

                    echo "<td> $value </td>";
                }
                echo "</tr>";

                $isFirstRow = TRUE;
            } else {
                // then print every subsequent row of values
                echo "<tr>";
                foreach ($item as $key => $value) {
                    if ($key == 'image_name') {
                        echo '<td><img class="tableImg" src="./data/' . $value . '"></td>';
                        continue;
                    }

                    if ($key == 'price') {
                        // Calculates the total price for the product
                        $price = $value * $item['quantity'];
                        echo '<td>' . $price . '</td>';

                        // Adds the price of the product to the total cost
                        $totalCost += $price;
                        continue;
                    }

                    echo "<td> $value </td>";
                }
                echo "</tr>";
            }
        }

        echo '<tr><td></td><td></td><td></td><td>Total Cost: </td><td>' . $totalCost . '</td><td></td></tr>';

        echo "</table>";
    }
}

/* 
    Checks if all fields in an array are filled
    return true if all fields are filled
*/
function validateArr($arr)
{
    foreach ($arr as $value) {
        if (!$value) return false;
    }

    return true;
}

/* 
    Function for displaying the shopping cart pages
    Gets the json data of the shoppingcart and displays it
    Only displays data if there are items in cart
*/
function shoppingCartPage()
{
    // Getting the data from the json file
    $jsonData = json_decode(file_get_contents('ShoppingCart.json'), true);

    // Guard clause to check if cart is empty
    if (!$jsonData) {
        echo 'No items in cart';
        return false;
    }

    // Getting information needed 
    $dbData = Database::readFromTable('products');
    $dataToCreateTableFrom = array();

    foreach ($jsonData as $item) {
        foreach ($dbData as $data) {
            if ($item['id'] == $data['product_id']) {
                $data['quantity'] = $item['quantity'];
                $dataToCreateTableFrom[] = $data;
            }
        }
    }

    createTable($dataToCreateTableFrom, 'cart');
    echo '
        <form action="" method="POST">
            <input type="submit" value="Checkout" name="payBtn">
        </form>
        ';


        
    // Creates form for deleting items in the orders table
    echo '
    <form method="POST" action="">
        <h2>Remove product from cart</h2>
        <label for="product_id">Product id:</label>
        <select name="product_id">';

    foreach ($jsonData as $key => $value) {
        echo "<option value=\"$key\">product no.${value['id']}</option>";
    }

    echo    '</select>
        <input type="submit" value="remove product" class="submit" name="removeBtn">
    </form>
    ';

    return true;
}
