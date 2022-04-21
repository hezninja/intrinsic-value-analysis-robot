<?php
date_default_timezone_set('Australia/Brisbane');

class Db {

    // The database connection
    protected static $connection;

    public function connect() {

        $environment = "production"; // 'local', 'production'

        switch ($environment) {
            case "local":
                $user = "root";
                $password = "root";
                $db = "ivar";
                break;

            case "production":
                $user = "";
                $password = "";
                $db = "";
                break;
        }

        // Try and connect to the database
        if(!isset(self::$connection)) {
            self::$connection = new mysqli("localhost",$user,$password,$db);
        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            return false;
        }
        return self::$connection;
    }

    public function query($query) {

        // Connect to the database
        $connection = $this->connect();

        // Query the database
        $result = $connection->query($query);

        return $result;
    }

    public function select($query) {
        $rows = array();
        $result = $this->query($query);
        if($result === false) {
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function error() {
        $connection = $this->connect();
        return $connection->error;
    }

    public function quote($value) {
        $connection = $this->connect();
        return "'" . $connection->real_escape_string($value) . "'";
    }
}