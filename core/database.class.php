<?php

class Database {

    private static $database;
    private $link;

    public static function mysqli() {
        if (!self::$database) {
            self::$database = new Database();
        }
        return self::$database;
    }

    public function getLink() {
        if (!$this->link) {
            // Connect to DB
            $this->link = mysqli_connect(HOST, USERNAME, PASSWORD) or die("Can't connect to DB.");
            mysqli_query($this->link, "SET NAMES 'utf8mb4'");
            mysqli_select_db($this->link, DATABASE) or die("Database not exists!");
        }
        return $this->link;
    }
}
