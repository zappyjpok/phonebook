<?php
/**
 * Created by PhpStorm.
 * user: thuyshawn
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
        $sessions = new SecureSessionHandler('PhoneBook_sessions');
        $sessions->start();
        $this->sessions = $sessions;

        $login = new Login();
        $this->login = $login;
        $this->loggedIn = $login->is_logged_in();
    }

    /**
     * Get model for the controller
     *
     * @param $model
     * @return mixed
     */

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

    /**
     * Get views from the view folder
     *
     * @param $view -- view
     * @param array $data -- parameters passed to the view
     */
    public function view($view, $data = [])
    {
        // Value to determine if the user is logged in
        $loggedIn = $this->loggedIn;

        require_once('../App/Views/' . $view . '.php');
    }

}

