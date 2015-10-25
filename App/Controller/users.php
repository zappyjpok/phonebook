<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 1/09/2015
 * Time: 7:42 PM
 */

require_once('../App/Library/Paths/Links.php');
require_once('../App/Library/ShoppingCart/GetShoppingCartValues.php');
require_once('../App/Library/Output/PrepareNavBar.php');

class users extends Controller {

    public function index()
    {


        $user = $this->model('User');
        $users = User::All();

        $this->view('user/index',
            [
                'users' => $users
            ]);
    }

    public function create()
    {
        $this->view('user/create');
    }

    public function store()
    {
        $this->model('User');
        User::Add($_POST['FirstName'], $_POST['LastName'], $_POST['Email'], $_POST['Password']);
        $link = Links::action_link('home/index');
        header('location: ' . $link);
    }

    public function login()
    {
        if($this->loggedIn)
        {
            $link = Links::action_link('home/index');
            header('location: ' . $link);
        }

        $token = $this->login->getToken();

        $this->view('user/login', [
            'token' => $token
        ]);
    }

    public function check()
    {
        $this->model('User');
        $user = User::authenticate($_POST['Email'], $_POST['Password']);
        if($user){
            $this->login->login($user);
            $link = Links::action_link('home/index');
            header('location: ' . $link);
        }else {
            $message = "Your email or password does not match our records";
            $this->view('user/login', [
                'message'   => $message,
                'email'     => $_POST['Email']
            ]);
        }
    }

    public function logout()
    {
        $this->login->logout();
        $link = Links::action_link('home/index');
        header('location: ' . $link);
    }
}