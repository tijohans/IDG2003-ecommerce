<?php
require_once "class_Database.php";

class Customer extends Database
{
    protected $firstname, $lastname, $address, $country, $customerId;

    public function __construct($firstname, $lastname, $address, $country)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->country = $country;

        $this->addCustomertToDB($this->firstname, $this->lastname, $this->address, $this->country);
    }

    /* 
        Method for adding customer to database
        Takes in customer information
    */
    protected function addCustomertToDB($firstname, $lastname, $address, $country)
    {
        $con = $this->connect();

        $firstname = parent::cleanVar($firstname, $con);
        $lastname = parent::cleanVar($lastname, $con);
        $address = parent::cleanVar($address, $con);
        $country = parent::cleanVar($country, $con);

        $query = "INSERT INTO customers(firstname, lastname, address, country) ";
        $query .= "VALUES ('$firstname', '$lastname', '$address', '$country')";
        
        $con->query($query);
        $this->customerId = mysqli_insert_id($con);
        $this->disconnect($con);
    }

    /* 
        Method for getting cusotmer id
    */
    public function getCustomerId() {
        return $this->customerId;
    }
}
