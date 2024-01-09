<?php

namespace App\Utilities;

use \PDO;

class DB {
    public function __construct($config) {
        $conn_str = "mysql:host=".$config['host'].";dbname=".$config['name'];
        $conn = new PDO($conn_str, $config['username'], $config['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}