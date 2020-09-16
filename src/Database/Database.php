<?php

namespace TwitterClone\Database;

use PDOException;
use PDO;

include $_SERVER['DOCUMENT_ROOT'] . '/twitter_clone/config/config.php';

//include '../../config/config.php';

class Database extends PDO
{

    private $host = DB_HOST, $user = DB_USER, $password = DB_PASS, $dbname = DB_NAME, $connection, $dbconnceted = false, $error;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $option = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT =>  true
        );

        try {
            $this->connection = new PDO($dsn, $this->user, $this->password, $option);
            $this->dbconnceted = true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->dbconnceted = false;
        }
    }

    public function getDB()
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }
    }
}
