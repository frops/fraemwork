<?php
/**
 * @author Ildar Asanov <i.asanov@corp.mail.ru>
 */

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();

$routes = require __DIR__ . '/routes.php';

$context = new Routing\RequestContext();
$context->fromRequest($request);

$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try {
    $request->attributes->add($matcher->match($request->getPathInfo()));
    $response = call_user_func($request->attributes->get('_controller') ?? 'render_template', $request);
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not found', 404);
} catch (Throwable $exception) {
    $response = new Response("An error occurred: " . $exception->getMessage(), 500);
}

$response->send();

/**
 * @param $request
 * @return Response
 */
function render_template(Request $request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    $_route = isset($_route) ? $_route : null;
    ob_start();
    include sprintf(__DIR__ . '/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}