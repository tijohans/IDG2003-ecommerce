<?php
require_once "class_Database.php";

class Product extends Database{
    // properties example, add more properties if needed
    protected $product_name, $description, $price, $imageName;

    public function __construct($name, $description, $price, $imageName)
    {
        $this->product_name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->imageName = $imageName;

        $this->addProductToDB($this->product_name, $this->description, $this->price, $this->imageName);
    }

    /* 
        Method for adding a product to database
        Takes in information about product
    */
    protected function addProductToDB($name, $description, $price, $image){
        $con = $this->connect();

        $name = parent::cleanVar($name, $con);
        $description = parent::cleanVar($description, $con);
        $price = parent::cleanVar($price, $con);
        $image = parent::cleanVar($image, $con);

        // For testing the sanitized input
        // echo $name, $description, $image, $price;

        $query = "INSERT INTO products(product_name, image_name, description, price) ";
        $query .= "VALUES ('$name', '$image', '$description', '$price')";
 
        $con->query($query);
        $this->disconnect($con);
    }
}

?>