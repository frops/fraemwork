<?php
/**
 * @author Ildar Asanov <i.asanov@corp.mail.ru>
 */

namespace Calendar\Controllers;


use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    public function exception(FlattenException $exception)
    {
        $msg = "Something wrong! ({$exception->getMessage()})";
        return new Response($msg, $exception->getStatusCode());
    }
}