<?php

namespace App\Middleware;

use Exception;
use CQ\DB\DB;
use CQ\Helpers\JWT;
use CQ\Response\Json;
use CQ\Middleware\Middleware;

class JWTMiddleware extends Middleware
{
    /**
     * Validate JWT token.
     *
     * @param $request
     * @param $next
     *
     * @return mixed
     */
    public function handle($request, $next)
    {
        $authorization_header = $request->getHeader('authorization')[0];
        $access_token = str_replace('Bearer ', '', $authorization_header);

        try {
            $credentials = JWT::valid('submission', $access_token);
        } catch (Exception $error) {
            return new Json([
                'success' => false,
                'errors' => [
                    'status' => 401,
                    'title' => 'JWT Error',
                    'detail' => $error->getMessage()
                ]
            ], 401);
        }

        $origin_header = $request->getHeader('origin')[0];
        if ($origin_header !== $credentials->allowed_origin) {
            return new Json([
                'success' => false,
                'errors' => [
                    'status' => 401,
                    'title' => 'invalid_origin',
                    'detail' => "Provided origin doesn't match allowed origin"
                ]
            ], 401);
        }

        if (!DB::has('templates', ['key_id' =>  $credentials->sub])) {
            return new Json([
                'success' => false,
                'errors' => [
                    'status' => 404,
                    'title' => 'template_not_found',
                    'detail' => 'this uuid is not connected to a template'
                ]
            ], 404);
        }

        $request->id = $credentials->sub;
        $request->origin = $origin_header;

        return $next($request);
    }
}
