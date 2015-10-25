<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 11/10/2015
 * Time: 7:01 PM
 */

require_once('../App/Model/SubNavbar.php');
require_once('../App/Model/Navbar.php');

class PrepareNavBar {

    protected $navBar;
    protected $subNavBar;
    protected $NavBarArray;

    /**
     * Get all values for the nav bar
     */
    public function __construct()
    {
        $this->navBar = Navbar::All();
    }


    public function getValues()
    {
        $this->createArray();
        return $this->NavBarArray;
    }

    private function createArray()
    {
        foreach($this->navBar as $row)
        {
            $sub = SubNavbar::All_Sub($row['navNavId']);

            foreach ($sub as $name)
            {
                $count = count($this->subNavBar);
                if($count < 1)
                {
                    $this->subNavBar [] = $name['snvName'];
                } else {
                    array_push($this->subNavBar, $name['snvName']);
                }

            }
            $this->NavBarArray [] = ['name'  => $row['navName'], 'sub' => [$this->subNavBar]];

            $this->subNavBar = [];
        }
    }

}