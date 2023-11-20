<?php

class Database {

    private $host = 'localhost';
    private $username = 'admin';
    private $password = 'admin';
    private $dbname = 'api_place';

    private $connection;

    public function __construct() {

        $this->connection = new PDO("pgsql:host=$this->host;dbname=$this->dbname", "$this->username", "$this->password");
    }

    public function getConnection()
    {
        return $this->connection;
    }
}