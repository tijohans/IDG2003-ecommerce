<?php 
    session_start();
    include './functions.php';
    require_once './classes/class_User.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<?php 
    displayNavBar();

    // Registering user
    if(isset($_POST['registerBtn'])){
        // Checking that all values in form are filled
        if(validateArr($_POST)) {
            print_r($_POST);
            $user = new User($_POST["username"], $_POST["email"], $_POST["password"]);
            header('location: login.php');
        } else {
            echo 'All fields need to be filled out';
        }            
    }

     
        // Form to show if the user is not registered
        echo '
            <form action="" method="POST">
                <h2>Register</h2>
                
                <label for="username">Username: </label>
                <input type="text" name="username">
                
                <label for="email">Email: </label>
                <input type="text" name="email">
                
                <label for="password">Password: </label>
                <input type="password" name="password">
                
                <input type="submit" value="register" name="registerBtn">
                <a href="login.php">Already a user?</a>
            </form>
        ';
    
    ?>

   
</body>
</html>