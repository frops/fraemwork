<?php
/**
 * @author Ildar Asanov <ifrops@gmail.com>
 */

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;

$routes = require __DIR__ . '/routes.php';

$request = Request::createFromGlobals();

$context = new Routing\RequestContext();
$context->fromRequest($request);

$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

try {
    $request->attributes->add($matcher->match($request->getPathInfo()));

    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not found', 404);
}
//catch (Throwable $exception) {
//    //$response = new Response("An error occurred: " . $exception->getMessage(), 500);
//    $response = new Response();
//    $trace = $exception->getTraceAsString();
//    $trace = str_replace('#', '<br>', $trace);
//    $response->setContent('<h2 style="color:red;">' . $exception->getMessage() . '</h2><hr><p>' . $trace . '</p>');
//}

$response->send();

/**
 * @param $request
 * @return Response
 */
function render_template(Request $request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();

    /** @var string $_route */
    include sprintf(__DIR__ . '/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}