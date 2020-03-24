<?php

namespace App\Middleware;

use App\Helpers\JWTHelper;
use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class AuthenticationMiddleware
{
    /**
     * Validate JWT token.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        $authorization_header = $request->getHeader('Authorization');
        $access_token = Str::replaceFirst('Bearer ', '', $authorization_header);

        try {
            $credentials = JWTHelper::decode($access_token, 'access');
        } catch (Exception $error) {
            return response()->json(['error' => $error->getMessage()], 401);
        }

        $request->session_uuid = $credentials->token;
        $request->user_id = $credentials->sub;
        $request->role = $credentials->role;

        return $next($request);
    }
}
