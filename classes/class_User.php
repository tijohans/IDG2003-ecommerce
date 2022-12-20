<?php 

require_once "class_Database.php";

class User extends Database 
{
    protected $username, $email, $password;

    /* 
        Constructs a user when the user class is called
    */
    public function __construct($username, $email, $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;

        $this->createUser($this->username, $this->email, $this->password);
    }

    /* 
        Method for creating the admin user
        Runs only once
    */
    public static function createAdminUser()
    {
        $con = parent::connect();
        $query = "SELECT username FROM users WHERE username=admin";
        $result = $con->query($query);

        // Stopping the method if the admin user already exists
        if($result) return; 

        $password = password_hash('0admin0', PASSWORD_DEFAULT);

        $query = "INSERT INTO users(username, email, password, role)";
        $query .= "VALUES ('admin', 'admin@ecommerce.com', '$password', 0)";

        $con->query($query);
        parent::disconnect($con);
    }

    /* 
        Method for creating a user
        Takes in relevant information about the user, 
        and cleans the data which is to be pushed to the database
    */
    public function createUser($username, $email, $password, $role = 1)
    {
        $con = $this->connect();

        $username = parent::cleanVar($username, $con);
        $email = parent::cleanVar($email, $con);
        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users(username, email, password, role) ";
        $query .= "VALUES ('$username', '$email', '$password', '$role')";
        
        $con->query($query);
        $this->disconnect($con);
    }
    
    /* 
        Method for logging in the user
        Returns either true or false
        Takes in username and password,
        and matches the password typed, 
        to the password stored in the database
    */
    public static function logInUser($userName, $password) : bool
    {
        $query = "SELECT password FROM users WHERE username='$userName'";
        $result = parent::runQuery($query);
        $row = mysqli_fetch_assoc($result);
        $dbpassword = $row['password'];
        $correctPassword = password_verify($password, $dbpassword);
        
        // If the user typed the wrong password returns false
        if(!$correctPassword) return false;

        // If the password is correct, creates a session on the user, and returns true
        self::createUserSession($userName);
        return true;
    }

    /* 
        Method for creating a user session
        Takes in username
        Uses username to get relevant information from the user, 
        and creates a session based on this
    */
    protected static function createUserSession($username)
    {
        $con = parent::connect();
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_fetch_assoc($con->query($query));

        // Setting session variables
        $_SESSION['username'] = $result['username'];
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['role'] = $result['role'];
        $_SESSION["customer_id"] = $result['customer_id'];
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];

        parent::disconnect($con);
    }

    /* 
        Method for adding a customer id to an existing user
        takes in username, and the customerId to be assigned
    */
    public static function addCustomerIdToUser($username, $customerId) {
        $con = parent::connect();

        // Updates the user to have a customer associated with their user
        $query = "UPDATE users SET customer_id=$customerId WHERE username='$username'";

        $con->query($query);
        parent::disconnect($con);

        // Refreshes user session after customer_id has been added
        self::createUserSession($username);
    }
}

?>