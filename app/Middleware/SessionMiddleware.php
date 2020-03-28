<?php

namespace App\Middleware;

use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\JsonResponse;

class SessionMiddleware implements Middleware
{
    /**
     * Validate PHP session.
     *
     * @param Request $request
     * @param $next
     *
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, $next)
    {
        $ip_match = $_SESSION['ip'] === $_SERVER['REMOTE_ADDR'];
        $session_valid = time() - $_SESSION['last_activity'] < config('auth')['session_expires'];

        if (!$ip_match || !$session_valid) {
            session_destroy();
            session_start();

            if ($request->isJSON) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => [
                        'status' => 403,
                        'title' => 'invalid_session',
                        'detail' => 'Session expired or IP mismatch'
                    ]
                ], 403);
            }

            return new RedirectResponse('/auth/logout', 403);
        }

        $_SESSION['last_activity'] = time();

        return $next($request);
    }
}
