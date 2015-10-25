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


    public function __construct()
    {
        $sessions = new SecureSessionHandler('Shopping_Cart');
        $sessions->start();
        $this->sessions = $sessions;

        $login = new Login();
        $this->login = $login;
        $this->loggedIn = $login->is_logged_in();
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
        //$loggedIn = $this->loggedIn;
        require_once('../App/Views/' . $view . '.php');
    }

}

