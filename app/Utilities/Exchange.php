<?php
    namespace App\Utilities;

    class Exchange{
        public $dollarToArsExchange;

        public function __construct($dollarToArsExchange){
            $this->dollarToArsExchange = $dollarToArsExchange;
        }

        public function getDollarPrice($product){
            if($product['price'] > 0){
                $dollarPrice = $product['price'] / $this->dollarToArsExchange;
                $formatedPrice = number_format($dollarPrice, 3, ',', '.');
                return $formatedPrice;
            }

            return 0;
        }

        public function getArsPrice($product){
            return $product['price'];
        }
    }
?>