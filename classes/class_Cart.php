<?php 


require_once "class_Database.php";

class Cart extends Database 
{

    /* 
        Method for adding items to cart
        Takes in an array of items
    */
    public function addToCart(array $items) {
        // Creates JSON file based on content of cart array
        // Needs to push items in an array to make the
        $this->createJson(array($items));

        // Setting the cookie to 1
        $this->set_cookie();
    }

    /* 
        Method for getting the number of items already in cart
    */
    public static function getNumberOfItemsInCart() {
        $file = file_get_contents('./ShoppingCart.json');
        $jsondata = json_decode($file, true);
        return count($jsondata);
    }   

    /* 
        Method for setting cookies
        Sets the cookie to expire in one week
    */
    protected function set_cookie() {
        setcookie("cart", 1, time()+604800, 
		  "/", "localhost", false, "httponly");
    }

    /* 
        Method for deleting the cart cookies
        Setting the time to the past
    */
    public static function delete_cookie() {
        setcookie("cart", 0, time()-1, 
		  "/", "localhost", false, "httponly");
    }

    /* 
        Method for creating json file
        Takes in cart information, and saves this in json
    */
    protected function createJson(array $items)
    { 
        $item = $items[0];
        
        // Checks if there is information in the json file already
        if(filesize('ShoppingCart.json') == 0){
            $data_to_save = json_encode($items);
        } else {

            // Takes the old records out from the json file
            $old_records = json_decode(file_get_contents('ShoppingCart.json'), true);
            $item = $items[0];

            $pushItem = true;
            foreach($old_records as $key => $record) {
                if($record['id'] == $item['id']){
                    $old_records[$key]['quantity'] += $item['quantity'];
                    $pushItem = false;
                }
            }

            if($pushItem) {
                $old_records[] = $item;
            }

            $data_to_save = json_encode($old_records);
        }

        // 
        file_put_contents('ShoppingCart.json', $data_to_save);
    }
}
