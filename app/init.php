<?php
/**
 * @author Ildar Asanov <ifrops@gmail.com>
 */

require_once __DIR__ . '/vendor/autoload.php';

use Simplex\ContentLengthListener;
use Simplex\Framework;
use Simplex\GoogleListener;
use Simplex\StringResponseListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;

$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes = require __DIR__ . '/routes.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));

$dispatcher->addSubscriber(new HttpKernel\EventListener\ExceptionListener('Calendar\Controllers\ErrorController::exception'));
$dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('utf-8'));
$dispatcher->addSubscriber(new HttpKernel\EventListener\StreamedResponseListener());
$dispatcher->addSubscriber(new StringResponseListener());

$framework = new \Simplex\Framework($dispatcher, $controllerResolver, $requestStack, $argumentResolver);

$response = $framework->handle($request);
$response->send();