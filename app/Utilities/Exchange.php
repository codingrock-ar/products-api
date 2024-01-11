<?php
    class Exchange{
        private $price;

        public function __construct(){
        }

        public function setPrice(){
            
        }

        public function getArsPrice($product){
            return $product->price;
        }

        public function getDollarPrice($product){
            return $product->price * $this->dollarToArsExchange;
        }
    }
?>