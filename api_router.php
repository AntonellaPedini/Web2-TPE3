<?php

require_once __DIR__ . '/libreria/Router/router.php';
require_once __DIR__ . '/app/controllers/obrasApiController.php';
require_once __DIR__ . '/libreria/jwt/jwt.middleware.php';
require_once __DIR__ . '/app/controllers/authApiController.php';

$router = new Router();
$router->addMiddleware(new JWTMiddleware());

// define la tabla de ruteo
$router->addRoute('login', 'POST', 'authApiController', 'login');
$router->addRoute('obras', 'GET', 'obrasApiController', 'getObras');
$router->addRoute('obras/:id', 'GET', 'obrasApiController', 'getObraById');
$router->addRoute('obras/:id', 'DELETE', 'obrasApiController', 'removeObra');
$router->addRoute('obras', 'POST', 'obrasApiController', 'insertObra');
$router->addRoute('obras/:id', 'PUT', 'obrasApiController', 'updateObra');
$router->addRoute('obras/:id', 'PATCH', 'obrasApiController', 'patchObra'); 

// rutea según recurso y método de la solicitud
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);

//http://localhost/web2/GaleriaDeArte_P3/api/ url base para acceder a la API, luego se le agrega el recurso y el método HTTP para acceder a cada endpoint
