<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 25/07/2015
 * Time: 2:59 PM
 */

require_once('../App/Library/Paths/Links.php');


class ShoppingCart {

    /**
     * @var array -- creates an array of error messages for testing purposes
     */
    protected $message = [];

    /**
     * @var -- stores the id of the order
     */
    protected $item;

    /**
     * @var -- stores the quantity of the order
     */
    protected $quantity;

    /**
     * @var bool -- checks if the values that were entered into the add ucntion are correct
     */
    protected $testsResult = false;

    // Values to check before adding to the session
    protected $isNull = true;
    protected $isNumeric = false;
    protected $isInt = false;

    /**
     * @var SecureSessionHandler -- creates a secure
     */
    protected $sessions;

    /**
     * Constructor creates a secure session handler
     */
    public function __construct()
    {
        $this->sessions = new SecureSessionHandler('shopping');
    }

    /**
     * Removes all items from the shopping cart
     */
    public function removeAllItems()
    {
        $this->sessions->destroy('cart');
        $this->sessions->destroy('cart_last_updated_time');
        $this->sessions->destroy('cart_activation_time');
    }

    /**
     * Removes a single item from the cart
     *
     * @param $id
     */
    public function removeItem($id)
    {
        $this->item = $id;
        $this->deleteSession();
        $this->sessions->put('cart_last_updated_time', time());
    }

    /**
     * Updates the quantity of items requested
     *
     * @param $id -- of the product to update
     * @param $quantity -- new quantity
     */
    public function updateQuantity($id, $quantity)
    {
        $this->item = $id;
        $this->quantity = $quantity;
        $this->deleteSession();
        $this->runTests();
        if($this->testsResult === true)
        {
            $this->addToSession();
        }
    }

    /**
     * Returns the number of items requested
     *
     * @return int
     */
    public function numberOfItems()
    {
        return count($this->sessions->get('cart'));
    }

    /**
     * Add items to the array
     *
     * @param $item
     * @param $quantity
     */
    public function addItem($item, $quantity)
    {
        $this->item = $item;
        $this->quantity = $quantity;
        // Run Tests to make sure the input is valid
        $this->runTests();
        if($this->testsResult === true)
        {
            $this->addToSession();
        }

    }

    /**
     * Get the time the session was created
     *
     * @return null|string
     */
    public function getTimeFromActivation()
    {
        $time = null;

        if($this->sessions->get('cart_activation_time') !== null)
        {

            $time = $this->timeDifference($this->sessions->get('cart_activation_time'));
        }

        return $time;
    }

    /**
     * Get the time the session was last updated
     *
     * @return string
     */
    public function getTimeFromLastUpdate()
    {
        $updateTime = $this->sessions->get('cart_last_updated_time');
        if($updateTime !== null)
        {
            return $this->timeDifference($this->sessions->get('cart_last_updated_time'));
        }
        return $this->timeDifference($this->sessions->get('cart_activation_time'));
    }

    /**
     * Gets the number of minutes between the time the Session was
     * created and now
     *
     * @param $start
     * @return string
     */
    private function timeDifference($start)
    {
        $begin = new DateTime(date('h:i:s', $start));
        $end = new DateTime(date('h:i:s', time()));

        $duration = $begin->diff($end);

        return $duration->format('%i');
    }

    /**
     * Checks if the variables numbers
     */
    private function runTests()
    {
        $this->checkIfNull();
        if($this->isNull === false) { $this->checkIfNumbers(); }
        if($this->isNumeric === true) {
            $this->convertToInt();
            $this->testsResult = true;
        }

    }

    /**
     * Checks if a value is null;
     */
    private function checkIfNull()
    {
        $array = [$this->item, $this->quantity];
        foreach($array as $value) {
            if (is_null($value)) {
                $this->message [] = "Failure: one was null";
                $this->isNull = true;
                break;
            } else {
                $this->message [] = "Success: they both are not null";
                $this->isNull = false;
            }
        }
    }

    /**
     * checks if a value is a number
     */
    private function checkIfNumbers()
    {
        $array = [$this->item, $this->quantity];
        foreach($array as $value) {
            if (!is_numeric($value)) {
                $this->message [] = "Failure: one was not numeric";
                $this->isNumeric = false;
                break;
            } else {
                $this->message [] = "Success: they are numeric";
                $this->isNumeric = true;
            }
        }
    }

    /**
     * converts the value to an integer
     */
    private function convertToInt()
    {
        if(!is_int($this->item))
        {
            $this->message [] = 'The item was not an int, but it has been converted';
            $this->item = (int)$this->item;
        }
        if(!is_int($this->quantity))
        {
            $this->message [] = 'The quantity was not an int, but it has been converted';
            $this->quantity = (int)$this->quantity;
        }
        $this->isInt = true;
    }

    /**
     * Add an item to the shopping cart
     */
    private function addToSession()
    {
        if($this->checkIfEmpty() === false)
        {
            $count = count($this->sessions->get('cart')) + 1;
            if($this->checkIfInArray())
            {
                // Find the new quantity
                $total = $this->getNewTotal();
                // update the session using a sessions class
                $this->deleteSession();
                $this->sessions->push('cart', ['item' => $count, ['id' => $this->item, 'quantity' => $total]], true);
            } else {
                $this->message [] = "There are $count items in the array session";
                $this->sessions->push('cart', ['item' => $count, ['id' => $this->item, 'quantity' => $this->quantity]], true);
            }
        } else {
            $this->sessions->push('cart', ['item' => 1, ['id' => $this->item, 'quantity' => $this->quantity]], true);
        }
    }

    /**
     * Checks if their are items in the cart
     *
     * @return bool
     */
    private function checkIfEmpty()
    {
        $array = $this->sessions->get('cart');
        if(!isset($array))
        {
            $this->message [] = 'The cart is empty';
            return true;
        } else {

            $this->message [] = 'The cart is not empty';
            return false;
        }
    }

    /**
     * Checks if the item has already been selected
     *
     * @return bool
     */
    private function checkIfInArray()
    {
        $check = false;
        if(!$this->checkIfEmpty())
        {
            foreach($this->sessions->get('cart') as $each_item)
            {
                foreach($each_item as $each_value)
                {

                    if($each_value['id'] == $this->item)
                    {
                        $this->message [] = 'Neutral: The value was in the array';
                        $check = true;
                    }
                }
            }
        }
        return $check;
    }

    /**
     * Gets the new total
     *
     * @return int
     */
    private function getNewTotal()
    {
        $total = 0;
        foreach($this->sessions->get('cart') as $each_item) {
            foreach ($each_item as $each_value) {
                if ($each_value['id'] == $this->item) {
                    $firstQuantity = $each_value['quantity'];
                    $secondQuantity = $this->quantity;
                    $total = $firstQuantity + $secondQuantity;
                }
            }
        }
        $this->message [] = "The new total is $total";
        return $total;
    }

    /**
     * This function removes an item from the cart array.
     * The item variable is set to the id that needs to be removed
     * A foreach loop will go through every value until it finds the one that \
     * must be removed from the cart.
     *
     */

    private function deleteSession()
    {
        foreach($this->sessions->get('cart') as $key => $value)
        {
            if($value[0]['id'] == $this->item)
            {
                unset($_SESSION['cart'][$key]);
            }

        }
    }

    public function getMessages()
    {
        if(!empty($this->message))
        {
            return $this->message;
        } else {
            return 'success';
        }
    }
}