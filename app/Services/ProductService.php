<?php

namespace App\Services;

use App\DataAccess\ProductDAO;

class ProductService {
    private $productDAO;

    public function __construct($c) {
        $this->productDAO = new ProductDAO($c);
    }

    public function getAllProducts() {
        return $this->productDAO->getAllProducts();
    }

    public function getProductById($id) {
        return $this->productDAO->getProductById($id);
    }

    public function createProduct($name, $price) {
        return $this->productDAO->createProduct($name, $price);
    }

    public function updateProduct($id, $name, $price) {
        return $this->productDAO->updateProduct($id, $name, $price);
    }

    public function deleteProduct($id) {
        return $this->productDAO->deleteProduct($id);
    }
}

?>