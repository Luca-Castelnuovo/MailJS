<?php

namespace App\Controllers;

use Exception;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\RedirectResponse;
use League\OAuth2\Client\Provider\Github;

class AuthController extends Controller
{
    private $provider;

    /**
     * Initialize the OAuth provider
     * 
     * @return void
     */
    public function __construct()
    {
        $this->provider = new Github([
            'clientId'     => config('oauth')['client_id'],
            'clientSecret' => config('oauth')['client_secret'],
            'redirectUri'  => config('oauth')['redirect_url'],
        ]);
    }

    /**
     * Login start OAuth
     *
     * @return RedirectResponse
     */
    public function login(ServerRequest $request)
    {
        $authUrl = $this->provider->getAuthorizationUrl();
        $_SESSION['state'] = $this->provider->getState();

        return new RedirectResponse($authUrl);
    }

    /**
     * Callback for OAuth
     *
     * @return RedirectResponse
     */
    public function callback(ServerRequest $request)
    {
        $state = $request->getQueryParams()['state'];
        $code = $request->getQueryParams()['code'];

        if (empty($state) || ($state !== $_SESSION['state'])) {
            return $this->logout('invalid state');
        }
        unset($_SESSION['state']);

        try {
            $token = $this->provider->getAccessToken('authorization_code', ['code' => $code]);
            $user_id = $this->provider->getResourceOwner($token)->getNickname(); // TODO: escape the user_id

            if (!in_array($user_id, config('oauth')['allowed_users'])) {
                return $this->logout('account not allowed');
            }

            $_SESSION['user_id'] = $user_id;
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        } catch (Exception $e) {
            return $this->logout($e);
        }

        return new RedirectResponse('/dashboard');
    }

    /**
     * Destroy session
     * 
     * @param string $message optional
     *
     * @return RedirectResponse
     */
    public function logout($message = null)
    {
        session_destroy();
        session_start();

        if ($message !== null) {
            return new RedirectResponse('/?message=' . $message); // TODO: read this and display on the frontend (like in a clossable banner above the login btn)
        }

        return new RedirectResponse('/');
    }
}
