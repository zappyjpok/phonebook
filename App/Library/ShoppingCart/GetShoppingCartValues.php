<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 28/07/2015
 * Time: 6:25 PM
 */

require_once('../App/Model/Product.php');

class GetShoppingCartValues {

    protected $products = [];
    protected $messages = [];
    protected $prices = [];
    protected $total = 0;
    protected $sessions;

    /**
     * Be able to access sessions
     */
    public function __construct()
    {
        $this->sessions = new SecureSessionHandler('shopping');
    }

    /**
     * Gets the product information in an array
     *
     * @return array
     */
    public function getProductInformation()
    {
        $this->queryDatabase();
        return $this->products;
    }

    /**
     * return the total
     *
     * @return int
     */
    public function getTotal()
    {
        $this->createTotal();
        return $this->total;
    }

    /**
     * Queries the database adds the values to the products array
     *
     *
     */
    private function queryDatabase()
    {
        if(!is_null($this->sessions->get('cart')))
        {
            foreach($this->sessions->get('cart') as $each_item)
            {
                foreach ($each_item as $key => $value)
                {
                    if(!is_null($value['id']))
                    {
                        // query the product information using the product id
                        $product = Product::find($value['id']);

                        if(!is_null($value['quantity']) && $value['quantity'] > 0)
                        {
                            $this->prices []  = $this->getPrice($value['quantity'], $product['proPrice']);
                            $this->products [] = [
                                'id'            => $value['id'],
                                'quantity'      => $value['quantity'],
                                'name'         => $product['proName'],
                                'description'   => $product['proDesc'],
                                'image'         => $product['proImage'],
                                'price'         => $product['proPrice']
                            ];
                        } // end if
                    } // end if
                } // end foreach
            } // end foreach
        } // end if
    }

    /**
     * Calculates price to include sales and quantity
     *
     * @param $id
     * @param $quantity
     * @param $price
     * @return int|string
     */
    private function getPrice($quantity, $price)
    {
        $total = number_format($quantity * $price, 2);

        return $total;
    }

    private function createTotal()
    {
        foreach ($this->prices as $price)
        {
            $this->total = $this->total + $price;
        }
    }


    /**
     * This returns an array that is used for testing purposes
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}