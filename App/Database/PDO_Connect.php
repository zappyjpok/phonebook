<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 30/08/2015
 * Time: 3:22 PM
 */

require('../ENV.php');


class PDO_Connect
{

    private $host = DB_SERVER;
    private $user = DB_USER;
    private $password = DB_PASS;
    private $dbName = DB_NAME;

    private $db;
    private $connected = false;
    private $errors = [];
    private $stmt;

    public function __construct()
    {
        // Set DSN
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbName . ";";

        // Set Options
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        //
        try {
            $this->db = new PDO($dsn, $this->user, $this->password, $options);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->stmt = $this->db->query($query);
    }

    public function prepare($query)
    {
        $this->stmt = $this->db->prepare($query);
    }

    public function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){
        return $this->stmt->execute();
    }

    public function result_set(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount(){
        return $this->stmt->rowCount();
    }

    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }

    private function test_database()
    {
        $errorInfo = $this->db->errorInfo();
        if (isset($errorInfo[2])) {
            $this->errors[] = $errorInfo[2];
        }
        if ($this->db)
        {
            $this->connected = true;
        } else {
            $this->errors[] = 'There was a problem connecting to the database';
        }
    }

    private function test_statement()
    {
        $errorInfo = $this->stmt->errorInfo();
        if (isset($errorInfo[2])) {
            $this->errors[] = $errorInfo[2];
        }
    }

    public function getErrors()
    {
        $this->test_database();
        $this->test_statement();
        return $this->errors;
    }
}

