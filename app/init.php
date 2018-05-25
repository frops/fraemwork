<?php
/**
 * @author Ildar Asanov <ifrops@gmail.com>
 */

require_once __DIR__ . '/vendor/autoload.php';

use Simplex\ContentLengthListener;
use Simplex\Framework;
use Simplex\GoogleListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;

$routes = require __DIR__ . '/routes.php';

//$request = Request::createFromGlobals();
$request = Request::create(isset($argv[1]) ? $argv[1] : '/is_leap_year/2012');

$context = new Routing\RequestContext();
$context->fromRequest($request);

$dispatcher = new EventDispatcher();

$dispatcher->addSubscriber(new GoogleListener());
$dispatcher->addSubscriber(new ContentLengthListener());

$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

$framework = new \Simplex\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);

/** @var Framework $framework */
$framework = new HttpKernel\HttpCache\HttpCache(
    $framework,
    new HttpKernel\HttpCache\Store(__DIR__ . '/cache')
);

$response = $framework->handle($request);

echo $response;
echo "\n";

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