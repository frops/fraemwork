<?php
/**
 * @author Ildar Asanov <i.asanov@corp.mail.ru>
 * @var string $_route
 */

require_once __DIR__.'/../app/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();

$routes = require __DIR__ . '/../app/routes.php';

$context = new Routing\RequestContext();
$context->fromRequest($request);

$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try {
    extract($matcher->match($request->getPathInfo()));
    ob_start();
    require sprintf(__DIR__ . '/../app/pages/%s.php', $_route);
    $response = new Response(ob_get_clean());
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not found', 404);
} catch (Throwable $exception) {
    $response = new Response("An error occurred", 500);
}

$response->send();