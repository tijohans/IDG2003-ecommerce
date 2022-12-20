<?php
session_start();
include "functions.php";

displayNavBar();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Main Page </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <?php 

    // Checks the get request for information about redirects from the login page
    if($_GET["loggedin"] == true) echo 'Log In successful!';
    if($_GET["loggedout"] == true) echo 'Log Out successful!';

    
    // Have a table to display current products
    // Names of products should be a hyperlink, than when clicked, will lead the user to the specific product page    
    $tableData = Database::readFromTable('products');
    createTable($tableData);
    ?>
</body>
</html>
    
    
    
    