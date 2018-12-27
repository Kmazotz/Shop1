<?php

namespace App\Core;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\Exception\FlattenException;

/**
 * ErrorController short summary.
 *
 * ErrorController description.
 *
 * @version 1.0
 * @author Usuario
 */
class ErrorController
{
    /**
     * Summary of exception
     * @param FlattenException $exception
     * @return Response
     */
    public function exception(FlattenException $exception): Response
    {
        $msg = 'Something went wrong! (' . $exception->getMessage() . ')';

        return new Response($msg, $exception->getStatusCode());
    }
}
