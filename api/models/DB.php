<?php

namespace Api\Models;

use PDO;
use PDOException;

require_once './config/database.php';

/**
 * Description of DB
 *
 * @author Soumyanjan
 */
class DB {

    private $connection = null;
    private $username = "";
    private $password = "";
    private $database = "";
    private $host = "";
    private $dsn = "";

    public function __construct() {
        $this->host = HOSTNAME;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->database = DB_NAME;
        $this->dsn = "mysql:host=$this->host;dbname=$this->database";

        try {
            $this->connection = new PDO($this->dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->connection;
    }

}
