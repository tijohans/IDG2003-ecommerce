<?php
session_start();
include "functions.php";
require_once './classes/class_User.php';

displayNavBar();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Login </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <?php 
    // First add a method in the User class for creating an admin account. 
    // This method should run only once, the first time, this page is loaded. 
    // Essentially, do a check on the database to check if an admin account exist. 
    User::createAdminUser();
    
    // Implement login functionality here
    if(isset($_POST["loginBtn"])){
        
        // Trying to login the user, returns either true or false
        $userLogin = User::logInUser($_POST["username"], $_POST["password"]);
        
        // Checking if the login is successful, then either redirects or gives message to user
        echo $userLogin ? header('location: ./mainPage.php?loggedin=true') : 'wrong username or password';
    }

    // Logging out the user, and redirecting them to main page
    if(isset($_POST["logoutBtn"])){
        session_destroy();
        header('location: ./mainPage.php?loggedout=true');
    }

    
    if($_SESSION["isLoggedIn"]) {
        echo ' <form action="" method="POST">
        <input type="submit" value="logout" name="logoutBtn">
        </form>';
    } else {
        echo '
        <form action="" method="POST">
            <h2>Login</h2>

            <label for="username">Username: </label>
            <input type="text" name="username">

            <label for="password">Password: </label>
            <input type="password" name="password">

            <input type="submit" value="login" name="loginBtn">
        </form> 
    ';
    }

    
    
    ?>
    
    
    
    
</body>
</html>