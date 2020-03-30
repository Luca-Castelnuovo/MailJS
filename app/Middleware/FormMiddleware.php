<?php

namespace App\Middleware;

use App\Helpers\StringHelper;
use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class FormMiddleware implements Middleware
{
    /**
     * If POST,PUT,PATCH requests contains formdata interpret it
     *
     * @param Request $request
     * @param $next
     *
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, $next)
    {
        if (in_array($request->getMethod(), ['POST', 'PUT'])) {
            if (!StringHelper::contains($request->getHeader('content-type')[0], 'form')) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => [
                        'status' => 400,
                        'title' => 'invalid_content_type',
                        'detail' => "Content-Type should be 'application/x-www-form-urlencoded'"
                    ]
                ], 400);
            }

            $request->data = (object) $request->getParsedBody();
            $request->isJSON = false;

            unset($request->data->authorization);
        }

        return $next($request);
    }
}
