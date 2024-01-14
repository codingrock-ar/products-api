<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductController{
    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->ProductService = $container->get('ProductService');
    }
    
    public function getProduct(Request $request, Response $response, $args): Response {
        $id = $args['id'];

        try {
            $product = $this->ProductService->getProductById($id);

            if($product){
                $response->getBody()->write(json_encode($product));
                return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
            }

            $error = [
                "message" => "Product not found"
            ];

            $response->getBody()->write(json_encode($error));
            
            return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(204);
        } catch (Exception $e) {
              $error = [
                  "message" => $e->getMessage()
              ];

              $response->getBody()->write(json_encode($error));
              return $response
              ->withHeader('content-type', 'application/json')
              ->withStatus(500);
        }
    }
    
    public function getProducts(Request $request, Response $response): Response {
        try {
            $products = $this->ProductService->getAllProducts();
            
            if($products){
                $response->getBody()->write(json_encode($products));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
            }

            $error = [
                "message" => "Products not found"
            ];

            $response->getBody()->write(json_encode($error));
            
            return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(204);
        } catch (Exception $e) {
            $error = [
                "message" => $e->getMessage()
            ];
       
            $response->getBody()->write(json_encode($error));
            return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
        }
    }
    
    public function createProduct(Request $request, Response $response, $args): Response {
        $data = $request->getParsedBody();
        $name = $data["name"];
        $price = $data["price"];

        try {
            $product_id = $this->ProductService->createProduct($name, $price);
            
            if($product_id){
                $response->getBody()->write(json_encode([
                    'id' => $product_id,
                    'name' => $name,
                    'price' => $price
                ]));
                
                return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(201);
            }

            $error = [
                "message" => "The Product couldn't be created"
            ];

            $response->getBody()->write(json_encode($error));
            
            return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(204);
        } catch (Exception $e) {
            $error = [
                "message" => $e->getMessage()
            ];
            
            $response->getBody()->write(json_encode($error));
            
            return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
        }   
    }
    
    public function editProduct(Request $request, Response $response, $args): Response {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $name = $data["name"];
        $price = $data["price"];

        try {
            $product = $this->ProductService->updateProduct($id, $name, $price);
            if($product){
                $response->getBody()->write(json_encode([
                    'id' => $id,
                    'name' => $name,
                    'price' => $price
                ]));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
            }

            $error = [
                "message" => "The Product couldn't be updated"
            ];

            $response->getBody()->write(json_encode($error));
            
            return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(204);
        } catch (Exception $e) {
            $error = [
                "message" => $e->getMessage()
            ];

            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
        }
    }
    
    public function deleteProduct(Request $request, Response $response, $args): Response {
        $id = $args["id"];
    
        try {
            $deleted = $this->ProductService->deleteProduct($id);
            $response->getBody()->write(json_encode($id));
            
            if($deleted){
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
            }

            $error = [
                "message" => "The Product couldn't be deleted"
            ];

            $response->getBody()->write(json_encode($error));
            
            return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(204);
        } catch (Exception $e) {
            $error = [
                "message" => $e->getMessage()
            ];
    
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
        }
    }
}

?>