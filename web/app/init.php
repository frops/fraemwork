<?php
/**
 * @author Ildar Asanov <i.asanov@corp.mail.ru>
 */

// framework/init.php
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$map = array(
    '/hello' => __DIR__ . '/../public/hello.php',
    '/bye'   => __DIR__ . '/../public/bye.php',
);