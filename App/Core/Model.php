<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 30/10/2015
 * Time: 11:59 AM
 */

namespace App\Core\Model;

require_once('../App/Database/PDO_Connect.php');



class Model {

    /**
     * Database connection to all models
     *
     * @var
     */
    protected $db;

    public function __construct()
    {
        $this->db = new PDO_Connect();
    }

}