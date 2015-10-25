<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 1/09/2015
 * Time: 6:52 PM
 */

class home extends Controller
{
    public function index()
    {
        $this->view('home/index');
    }

}