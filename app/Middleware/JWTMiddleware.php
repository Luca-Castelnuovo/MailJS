<?php

namespace App\Middleware;

use DB;
use Exception;
use App\Helpers\JWTHelper;
use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class JWTMiddleware implements Middleware
{
    /**
     * Validate JWT token.
     *
     * @param Request $request
     * @param $next
     *
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, $next)
    {
        $authorization_header = $request->getHeader('authorization')[0];
        $authorization_form = $request->getParsedBody()['authorization'];
        $authorization_bearer = $authorization_header ?: $authorization_form;

        $access_token = str_replace('Bearer ', '', $authorization_bearer);

        try {
            $credentials = JWTHelper::valid('submission', $access_token);
        } catch (Exception $error) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    'status' => 401,
                    'title' => 'JWT Error',
                    'detail' => $error->getMessage()
                ]
            ], 401);
        }

        // TODO: fix origin system
        $origin_header = $request->getHeader('origin')[0];
        $referer_header = $request->getHeader('referer')[0];
        // if ($origin_header !== $credentials->allowed_origin) {
        // return new JsonResponse([
        //     'success' => false,
        //     'errors' => [
        //         'status' => 401,
        //         'title' => 'invalid_origin',
        //         'detail' => "Provided origin doesn't match allowed origin"
        //     ]
        // ], 401);
        // }

        if (!DB::has('templates', ['uuid' =>  $credentials->sub])) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    'status' => 401,
                    'title' => 'template_not_found',
                    'detail' => 'this uuid is not connected to a template'
                ]
            ], 401);
        }

        $request->uuid = $credentials->sub;
        $request->origin = $origin_header;
        $request->referer = $referer_header;

        return $next($request);
    }
}
