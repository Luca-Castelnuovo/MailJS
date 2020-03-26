<?php

namespace App\Middleware;

use App\Helpers\JWTHelper;
use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class AuthMiddleware implements Middleware
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
        // $authorization_header = $request->getHeader('Authorization');
        // $access_token = Str::replaceFirst('Bearer ', '', $authorization_header);

        // try {
        //     $credentials = JWTHelper::decode($access_token, 'access');
        // } catch (Exception $error) {
        // return new JsonResponse(['error' => $error->getMessage()], 401);
        // }

        // $origin_header = $request->getHeader('Origin');
        // if ($origin_header !== $credentials->allowed_origin) {
        // return new JsonResponse(['error' => 'invalid origin'], 401);
        // }

        // $request->user_id = $credentials->sub;
        // $request->template_id = $credentials->template_id;

        return $next($request);
    }
}
