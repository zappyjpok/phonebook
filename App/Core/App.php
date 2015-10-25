<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 1/09/2015
 * Time: 6:21 PM
 */

class App
{
    /**
     * Variables that will define the default controller and method of the url
     * Parameters can be added to the end of the URL to pass values to the next
     * page
     *
     */
    //default controller
    protected $controller = 'home';
    //default method
    protected $method = 'index';
    // parameters
    protected $parameters = [];

    /**
     * This will take the url and set the first parameter to controller if exists
     * The second parameter will be the method in the controller if exists
     * The third will be the parameters
     */
    public function __construct()
    {
        $url = $this->parseUrl();
        // check if the controller exists

        if(file_exists('../App/Controller/' . $url[0] . '.php' ))
        {
            // sets new controller
            $this->controller = $url[0];
            unset($url[0]);
        }
        // Require in the controller
        require_once('../App/Controller/' . $this->controller . '.php');

        $this->controller = new $this->controller;

        //check if method in the controller exists or index if default
        if(isset($url[1]))
        {
            if(method_exists($this->controller, $url[1]))
            {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        // adds parameters to value if it is not empty
        $this->parameters = $url ? array_values($url) : [];

        // call method in a the controller
        call_user_func_array([$this->controller, $this->method], $this->parameters);
    }

    /**
     * $_GET['url'] is everything after public in the url string.  This function will
     * sanitise the url, remove the last '/', and explode it on '/';
     *
     * @return url -- array [controller, method, parameters]
     */
    public function parseUrl()
    {
        if(isset($_GET['url']))
        {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}