<?php

namespace App\DataAccess;

class ProductDAO {
    private $db;

    public function __construct($c) {
        $this->db = $c->get('db');
        $this->logger = $c->get('logger');
    }

    public function getAllProducts() {
        $sql = "SELECT * FROM products";
        $res = $this->db->executeQuery($sql);

        if($res->num_rows > 0)
            return $this->db->fetchArray($res);

        return false;
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = $id";
        $res = $this->db->executeQuery($sql);

        if($res->num_rows > 0)
            return $this->db->fetchArray($res);

        return false;
    }

    public function createProduct($name, $price) {
        $name = $this->db->escape($name);
        $price = $this->db->escape($price);

        $sql = "INSERT INTO products (name, price) VALUES ('$name', '$price')";
        $res = $this->db->executeQuery($sql);

        if($this->db->affectedRows() > 0){}
            return $this->db->getInsertId();

        return false;
    }

    public function updateProduct($id, $name, $price) {
        $name = $this->db->escape($name);
        $price = $this->db->escape($price);

        $sql = "UPDATE products SET name='$name', price='$price' WHERE id = $id";
        $res = $this->db->executeQuery($sql);

        if($this->db->affectedRows() > 0)
            return $res;

        return false;
    }

    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = $id";
        $res = $this->db->executeQuery($sql);

        if($this->db->affectedRows() > 0)
            return $res;

        return false;
    }
}

?>