<?php

namespace App\Utilities;

class DB {
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private $link;

    // Constructor
    public function __construct($config) {
        $this->host = $config['host'];
        $this->username = $config['username']; 
        $this->password = $config['password']; 
        $this->database = $config['name'];

        $this->connect();
    }

    // Method connect to the database
    public function connect(){
        if(!$this->link){
            $this->link = new \Mysqli($this->host, $this->username, $this->password, $this->database);
            if ($this->link->connect_error) {
                die("Connection failed: " . $this->link->connect_error);
            }
        }

        return $this->link;
    }
    
    // Method to execute SQL queries
    public function executeQuery($sql) {
        $result = $this->link->query($sql);
        if (!$result) {
            die("Query failed: " . $this->link->error);
        }
        return $result;
    }

    // Method to return the result num rows
    public function fetchArray($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Method to return the last insert id
    public function getInsertId() {
        return mysqli_insert_id($this->link);
    }

    // Method to return the last insert id
    public function affectedRows() {
        return mysqli_affected_rows($this->link);
    }

    // Method to return the result num rows
    public function getNumRows($result) {
        return mysqli_num_rows($result);
    }

    // Method to escape strings
    public function escape($str) {
        return mysqli_real_escape_string($this->link, $str);
    }

    // Method to close the connection
    public function close() {
        $this->link->close();
    }
}

?>