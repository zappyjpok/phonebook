<?php
/**
 * This class was modeled after the PDO class on cultt.com
 * Some alterations were made, but the full version of the class
 * is at http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
 *
 */

require('../ENV.php');


class PDO_Connect
{

    /**
     * @var string -- list of database variable
     */
    private $host = DB_SERVER;
    private $user = DB_USER;
    private $password = DB_PASS;
    private $dbName = DB_NAME;

    /**
     * @var PDO -- the database object
     */
    private $db;

    /**
     * @var bool -- check if database connection was established
     */
    private $connected = false;

    /**
     * @var array -- an array of error messages
     */
    private $errors = [];

    /**
     * @var -- the query statement
     */
    private $stmt;

    /**
     * Set up the database and check the connection
     */
    public function __construct()
    {
        // Set DSN
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbName . ";";

        // Set Options
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        // establish a connection to the database
        try {
            $this->db = new PDO($dsn, $this->user, $this->password, $options);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    /**
     * Send query to database
     *
     * @param $query
     */
    public function query($query)
    {
        $this->stmt = $this->db->query($query);
    }

    /**
     * Prepare a query to go to the database
     *
     * @param $query
     */
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
        return $this->db->lastInsertId();
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

