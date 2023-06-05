<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathMiddleware;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Selective\BasePath\BasePathDetector;
use Slim\Views\PhpRenderer;

require "../init.php";
require BASE_DIR . '/vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath("/timetracker"); // Aquí el nombre de la carpeta de tu proyecto

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

require BASE_DIR. "/src/rutas/api.php";


// Run app
$app->run();
?>