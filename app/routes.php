<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('hello', new Routing\Route('/hello/{name}', [
    'name' => 'World',
    '_controller' => function (Request $request) {
        $request->attributes->set('foo', 'bar');
        $response = render_template($request);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
]));

$routes->add('leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => 'Calendar\Controllers\LeapYearController::index'
]));

$routes->add('bye', new Routing\Route('/bye', [
    '_controller' => 'render_template'
]));

$routes->add('main', new Routing\Route('/'));


return $routes;