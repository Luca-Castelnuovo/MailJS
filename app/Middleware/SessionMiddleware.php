<?php

namespace App\Middleware;

use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

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
        $session_valid = time() - $_SESSION['LAST_ACTIVITY'] < config('auth')['session_expires'];

        if (!$ip_match || !$session_valid) {
            session_destroy();
            session_start();

            return new RedirectResponse('/auth/logout', 403);
        }

        $_SESSION['last_activity'] = time();

        return $next($request);
    }
}
