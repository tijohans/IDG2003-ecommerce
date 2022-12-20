<?php 

// superclass definition : Database
// contains Database related methods. 
class Database{
    
    /* 
        Method for connecting to database
    */
    protected function connect(){
        $host = 'localhost';
        $username = 'root';
        $password = 'root';
        $database = 'ecommerce';

        $connection = mysqli_connect($host,$username,$password,$database);

        if(!$connection){
            die ("Database connection failed");
        }

        return $connection;
    }
    
    /* 
        Method for disconnecting fromt he database
        Uses db conncetion to disconnect
    */
    protected function disconnect($connection){
        mysqli_close($connection);
    }
    
    /* 
        Method for getting data from table
        Takes in table name to read from
        Optionally takes in an argument
            This argument is the field name it wants to sort by
    */
    public static function readFromTable($tableName, $sortBy = false){
        $connection = self::connect();
        //query the database
        $query = "SELECT * FROM $tableName";

        if($sortBy) {
            $query .= " ORDER BY $sortBy";
        }

        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if(!$result){
            die('Query failed!' . mysqli_error($connection));
        }else {
            //echo "Entries Retrieved!<br>";
        }

        //read 1 row at a time
        $idx = 0;
        while($row=mysqli_fetch_assoc($result)){
            //print_r($row);echo "<br>";
            $resArray[$idx] = $row;
            $idx++;
        }

        Database::disconnect($connection);
        return $resArray;
    }

    /* 
        Method for getting data from two tables as a join
        Takes in two tables names to do an inner join on
    */
    public static function readFromJoin($table1, $table2)
    {
        $connection = self::connect();
        //query the database
        $query = "SELECT * FROM $table1 INNER JOIN $table2 ";

        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if(!$result){
            die('Query failed!' . mysqli_error($connection));
        }else {
            //echo "Entries Retrieved!<br>";
        }

        //read 1 row at a time
        $idx = 0;
        while($row=mysqli_fetch_assoc($result)){
            //print_r($row);echo "<br>";
            $resArray[$idx] = $row;
            $idx++;
        }

        Database::disconnect($connection);
        return $resArray;
    }

    /* 
        Method from running a query on the database
        Takes in an sql query to perform
        Returns the result
    */
    public static function runQuery($query){
        $con = self::connect();
        $res = mysqli_query($con, $query);
        return $res ? $res : die('query failed');
        self::disconnect($con);
    }
    
    /* 
        Method from sanitizing input
        Takes in a string as $var, and a database connection
        Returns the sanitized input
    */
    protected function cleanVar($var, $connection){
        $var = htmlentities($var); 
        $var = $connection->real_escape_string($var);
        return $var;
    }
}



?>