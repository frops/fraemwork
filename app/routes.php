<?php

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('hello', new Routing\Route('/hello/{name}', ['name' => 'World']));
$routes->add('buy', new Routing\Route('/buy'));
$routes->add('main', new Routing\Route('/'));

return $routes;