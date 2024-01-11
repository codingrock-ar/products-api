<?php

use DI\Container;
use App\Utilities\DB;
use App\Controllers\ProductController;
use App\Services\ProductService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/config/config.php';

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

$container->set('config', $config);

$container->set('db', function ($c) {
    return new DB($c->get('config')['db']);
});

$container->set('ProductService', function ($c) {
    return new ProductService($c);
});

// Define app routes
$app->get('/', function (Request $request, Response $response): Response {
    $response->getBody()->write("Route Not found");
    return $response;
});

$app->get('/products/{id}', ProductController::class . ':getProduct');
$app->get('/products', ProductController::class .':getProducts');
$app->post('/products', ProductController::class .':createProduct');
$app->put('/products/{id}', ProductController::class . ':editProduct');
$app->delete('/products/{id}', ProductController::class . ':deleteProduct');

// Run app
$app->run();