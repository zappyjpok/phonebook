<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 1/09/2015
 * Time: 6:23 PM
 */

class Controller
{
    protected $loggedIn;

    /**
     * @var SecureSessionHandler - Controllers can now access the session functions
     */
    protected $sessions;

    /**
     * @var login -- Controller now have access to login functions
     */
    protected $login;

    protected $shoppingCart;

    public function __construct()
    {
        $sessions = new SecureSessionHandler('Shopping_Cart');
        $sessions->start();
        $this->sessions = $sessions;

        $login = new Login();
        $this->login = $login;
        $this->loggedIn = $login->is_logged_in();

        $shoppingCart = new ShoppingCart();
        $this->shoppingCart = $shoppingCart;
    }

    public function model($model)
    {
        $path = '../App/Model/' . $model . '.php';
        if(file_exists($path))
        {
            require_once($path);
            return new $model();
        } else {
            echo 'Error: This model does not exist';
        }
    }
    public function view($view, $data = [])
    {
        $loggedIn = $this->loggedIn;
        $cart = $this->getCart();
        $startCart = $this->shoppingCart->getTimeFromActivation();
        $updateCart = $this->shoppingCart->getTimeFromLastUpdate();
        $nav = new PrepareNavBar();
        $navValues = $nav->getValues();

        require_once('../App/Views/' . $view . '.php');
    }

    public function JSON($data)
    {
        return json_encode($data);
    }

    protected function getCart()
    {
        $cart = 'You have 0 items in your shopping cart';
        if($this->shoppingCart->numberOfItems() > 0)
        {
            $count = $this->shoppingCart->numberOfItems();
            if($count === 1)
            {
                $cart = 'You have 1 item in your cart';
            } else {
                $cart = 'You have ' . $count . ' items in your cart';
            }
        }
        return $cart;
    }
}

