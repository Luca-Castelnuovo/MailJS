<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\RedirectResponse;
use League\OAuth2\Client\Provider\Github;

class AuthController extends Controller
{
    /**
     * Initialize the OAuth provider
     */
    public $provider;
    public function __construct() {
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

        if(empty($state) || ($state !== $_SESSION['state']))  {
            return $this->logout();
        }
    
        try {
            $token = $this->provider->getAccessToken('authorization_code', ['code' => $code]);
            $user = $this->provider->getResourceOwner($token);

            $_SESSION['logged_in'] = true;
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['id'] = $user->getNickname();
        } catch (Exception $error) {
            return $this->logout();
        }

        return new RedirectResponse('/dashboard');
    }

    /**
     * Destroy session
     *
     * @return RedirectResponse
     */
    public function logout(ServerRequest $request)
    {
        session_destroy();
        session_start();

        return new RedirectResponse('/');
    }
}
