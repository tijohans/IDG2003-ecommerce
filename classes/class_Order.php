<?php
require_once "class_Database.php";

class Order extends Database
{
    protected $customer_id, $product_id, $time, $quantity;

    public function __construct($customer_id, $product_id, $time, $quantity)
    {
        $this->customer_id = $customer_id;
        $this->product_id = $product_id;
        $this->time = time();
        $this->quantity = $quantity;

        $this->addOrderToDB($this->customer_id, $this->product_id, $this->time, $this->quantity);
    }

    /* 
        Method for adding an order to the database
        Takes in information about the order
    */
    protected function addOrderToDB($customer_id, $product_id, $time, $quantity)
    {
        $con = $this->connect();

        $customer_id = parent::cleanVar($customer_id, $con);
        $product_id = parent::cleanVar($product_id, $con);
        $time = parent::cleanVar($time, $con);
        $quantity = parent::cleanVar($quantity, $con);

        $query = "INSERT INTO orders(customer_id, product_id, time, quantity) ";
        $query .= "VALUES ('$customer_id', '$product_id', '$time', '$quantity')";

        $con->query($query);
        $this->disconnect($con);
    }

    /* 
        Method for creating orders on a customer
        Takes in a customer id
    */
    public static function createOrder($customerId): void
    {
        $jsondata = json_decode(file_get_contents('ShoppingCart.json'), true);
        foreach ($jsondata as $item) {
            $productid = $item['id'];
            $quantity = $item['quantity'];

            $order = new Order($customerId, $productid, time(), $quantity);
        }

        // Delete all data from json file by overwriting with nothing 
        file_put_contents('ShoppingCart.json', '');

        // Delete cookies
        Cart::delete_cookie();
    }
}
