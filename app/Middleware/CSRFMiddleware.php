<?php

namespace App\Middleware;

use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CSRFMiddleware implements Middleware
{
    /**
     * Validate CSRF
     *
     * @param Request $request
     * @param $next
     *
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, $next)
    {
        // check if providedd csrf token matches one stored in session

        // generate new token
        // add new token as header

        return $next($request);
    }
}
