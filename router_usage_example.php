<?php
// cli example usage: # echo '{"param3":"value3"}' |  php index.php --param1=value1 --param2=value2
// curl example usage: # curl -d '{"param3":"value3"}'  "http://test.loc:80/index.php?param1=value1&param2=value2"

require_once 'simplerouter/autoload.php';

use IvanHunko\SimpleRouter\Router;

$router = new Router();
$router->init();
$responseData = $router->getResponse();

$router->sendResponse($responseData);