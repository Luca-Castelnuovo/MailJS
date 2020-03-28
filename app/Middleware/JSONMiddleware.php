<?php

namespace App\Middleware;

use App\Helpers\StringHelper;
use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class JSONMiddleware implements Middleware
{
    /**
     * If POST,PUT,PATCH requests contains JSON interpret it
     * Also validate that the provided JSON is valid.
     *
     * @param Request $request
     * @param $next
     *
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, $next)
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])) {
            // Endpoints which don't recieve JSON data
            $bypass_filter = [
                'submission/form',
            ];

            // TODO: check why getUri() is empty
            if (in_array($request->getUri(), $bypass_filter)) {
                $request->data = $request->getParsedBody();

                return $next($request);
            }

            if (!StringHelper::contains($request->getHeader('content-type')[0], '/json')) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => [
                        'status' => 400,
                        'title' => 'invalid_content_type',
                        'detail' => "Content-Type should be 'application/json'",
                        'extra' => $request->getUri()
                    ]
                ], 400);
            }

            $data = json_decode($request->getBody()->getContents(), true);

            if ((JSON_ERROR_NONE !== json_last_error())) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => [
                        'status' => 400,
                        'title' => 'invalid_json',
                        'detail' => 'Problems parsing provided JSON'
                    ]
                ], 400);
            }

            $request->data = $data;
            $request->isJSON = true;
        }

        return $next($request);
    }
}
