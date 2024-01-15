<?php

namespace App\Services;

use App\DataAccess\ProductDAO;

class ProductService {
    private $productDAO;

    public function __construct($c) {
        $this->productDAO = new ProductDAO($c);
        $this->exchange = $c->get('exchange');
    }

    public function getAllProducts() {
        $products = $this->productDAO->getAllProducts();

        if($products){
            foreach($products as $key => $product){
                $products[$key]['dollarPrice'] = $this->exchange->getDollarPrice($product);
            }

            return $products;
        }

        return false;
    }

    public function getProductById($id) {
        $product = $this->productDAO->getProductById($id);
        
        if($product){
            $product['dollarPrice'] = $this->exchange->getDollarPrice($product);
        }
        
        return $product;
    }

    public function createProduct($name, $price) {
        $create = $this->productDAO->createProduct($name, $price);
        
        if($create){
            $product = [
                'id' => $create,
                'name' => $name,
                'price' => $price
            ];

            $product['dollarPrice'] = $this->exchange->getDollarPrice($product);

            return $product;            
        }
        
        return false;
    }

    public function updateProduct($id, $name, $price) {
        $update = $this->productDAO->updateProduct($id, $name, $price);
        
        if($update){
            $product = [
                'id' => $id,
                'name' => $name,
                'price' => $price
            ];

            $product['dollarPrice'] = $this->exchange->getDollarPrice($product);

            return $product;
        }

        return false;
    }

    public function deleteProduct($id) {
        return $this->productDAO->deleteProduct($id);
    }
}

?>