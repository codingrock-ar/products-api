<?php

use App\Utilities\DB;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/config.php';

// Create Container using PHP-DI
$container = new Container();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

// Parse json, form data and xml
$app->addBodyParsingMiddleware();

/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//$app->add(new BasePathMiddleware($app));

$container->set('config', $config);

$container->set('db', function ($c) {
    return new DB($c->get('config')['db']);
});

// Define app routes
$app->get('/', function (Request $request, Response $response): Response {
    $response->getBody()->write("Route Not found");
    return $response;
});

$app->get('/products/{id}', function (Request $request, Response $response, $args): Response {
    $id = $args['id'];
    $response->getBody()->write("Hello products get");
    return $response;
});

$app->get('/products', function (Request $request, Response $response): Response {
    $sql = "SELECT * FROM products";

    try {
      $db = $this->get('db');
      $stmt = $db->query($sql);
      $products = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;
     
      $response->getBody()->write(json_encode($products));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
        $error = [
            "message" => $e->getMessage()
        ];
   
        $response->getBody()->write(json_encode($error));
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

$app->post('/products', function (Request $request, Response $response, $args): Response {
    $data = $request->getParsedBody();
    $nombre = $data["nombre"];
    $precio = $data["precio"];
   
    $sql = "INSERT INTO products (nombre, precio) VALUES (:nombre, :precio)";
   
    try {
        $db = $this->get('db');
       
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':precio', $precio);
        
        $result = $stmt->execute();
        
        $db = null;
        $response->getBody()->write(json_encode($result));
        
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e) {
        $error = [
            "message" => $e->getMessage()
        ];
        
        $response->getBody()->write(json_encode($error));
        
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }   
});

$app->put('/products/{id}', function (Request $request, Response $response, $args): Response {
    $id = $request->getAttribute('id');
    $data = $request->getParsedBody();
    $nombre = $data["nombre"];
    $precio = $data["precio"];
   
    $sql = "UPDATE products SET
    nombre = :nombre,
    precio = :precio
    WHERE id = $id";
   
    try {
        $db = $this->get('db');
     
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':precio', $precio);

        $result = $stmt->execute();

        $db = null;
        echo "Update successful! ";

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = [
            "message" => $e->getMessage()
        ];

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->delete('/products/{id}', function (Request $request, Response $response, $args): Response {
    $id = $args["id"];

    $sql = "DELETE FROM products WHERE id = $id";

    try {
        $db = $this->get('db');

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();

        $db = null;
        $response->getBody()->write(json_encode($result));
        
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = [
            "message" => $e->getMessage()
        ];

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

// Run app
$app->run();