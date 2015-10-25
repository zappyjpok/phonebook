<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 1/09/2015
 * Time: 6:52 PM
 */

require_once('../App/Library/ShoppingCart/GetShoppingCartValues.php');
require_once('../App/Library/Output/PrepareNavBar.php');



class home extends Controller
{
    public function index()
    {
        $this->model('Product');

        $products = Product::All();

        $this->view('home/index', [
            'products'  =>  $products
        ]);
    }

    public function addToCart()
    {
       if($this->is_ajax())
       {
           // convert the id to an int
           $id = (int)$_POST['id'];
           // add to the shopping cart
           $this->shoppingCart->addItem($id,1);
           // get the new values to display
           $cart = $this->getCart();
           $startCart = $this->shoppingCart->getTimeFromActivation();
           $updateCart = $this->shoppingCart->getTimeFromLastUpdate();
           $data = [
               'cart'   => $cart,
               'start'  => $startCart,
               'update' =>$updateCart
           ];

           echo json_encode($data);
       }
    }

    public function addToCartForm($id)
    {
        $this->shoppingCart->addItem($id, $_POST['Quantity']);
        $link = Links::action_link('home/index');

        header('location: ' . $link);
    }

    public function show($id)
    {
        $this->model('Product');
        $product = Product::find($id);
        $quantity = $this->getQuantityArray();

        $this->view('home/show', [
            'product'   => $product,
            'quantity'  => $quantity
        ]);
    }

    public function cart()
    {
        $collect = new GetShoppingCartValues();
        $products = $collect->getProductInformation();
        $total = $collect->getTotal();
        $quantity = $this->getQuantityArray();

        $this->view('home/cart', [
            'products'  => $products,
            'total'     => $total,
            'quantity'  => $quantity
        ]);
    }

    public function update($id)
    {
        $this->shoppingCart->updateQuantity($id, $_POST['Quantity']);

        $link = Links::action_link('home/cart');

        header('location: ' . $link);
    }

    public function remove($id)
    {
        $this->shoppingCart->removeItem($id);
        $link = Links::action_link('home/cart');

        header('location: ' . $link);
    }

    public function delete_cart()
    {
        $this->shoppingCart->removeAllItems();

        $link = Links::action_link('home/cart');
        header('location: ' . $link);
    }

    public function destroy()
    {
        $this->sessions->forget();
        $link = Links::action_link('home/index');

        header('location: ' . $link);
    }

    private function getQuantityArray()
    {
        $quantity = [
           1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20
        ];

        return $quantity;
    }

    private function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

}