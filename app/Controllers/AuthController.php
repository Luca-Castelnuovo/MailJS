<?php

namespace App\Controllers;

use Exception;
use App\Controllers\Controller;
use lucacastelnuovo\Helpers\Str;
use lucacastelnuovo\Helpers\Session;
use lucacastelnuovo\Helpers\AppsClient;
use Zend\Diactoros\ServerRequest;

class AuthController extends Controller
{
    private $provider;

    /**
     * Initialize the provider
     * 
     * @return void
     */
    public function __construct()
    {
        $this->provider = new AppsClient([
            'app_id' => config('app.id'),
            'app_url'     => config('app.url')
        ]);
    }

    /**
     * Redirect to authorization portal
     * 
     * @return RedirectResponse
     */
    public function request()
    {
        $authUrl = $this->provider->getAuthorizationUrl();

        return $this->redirect($authUrl);
    }

    /**
     * Callback for OAuth
     *
     * @param ServerRequest $request
     * 
     * @return RedirectResponse
     */
    public function callback(ServerRequest $request)
    {
        $code = $request->getQueryParams()['code'];

        try {
            $data = $this->provider->getData($code, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
        } catch (Exception $e) {
            // var_dump($e->getMessage());exit;
            return $this->logout("token");
        }

        $id = Str::escape($data->sub); // Value is used in DB calls

        return $this->login($id, $data->variant, $data->exp);
    }

    /**
     * Create session
     * 
     * @param string $id
     * @param string $variant
     * @param string $expires
     *
     * @return RedirectResponse
     */
    public function login($id, $variant, $expires)
    {
        $return_to = Session::get('return_to');

        Session::destroy();
        Session::set('id', $id);
        Session::set('variant', $variant);
        Session::set('ip', $_SERVER['REMOTE_ADDR']);
        Session::set('expires', $expires);

        if (!file_exists("users/{$id}")) {
            mkdir("users/{$id}", 0770);
        }

        if ($return_to) {
            return $this->redirect($return_to);
        }

        return $this->redirect('/dashboard');
    }

    /**
     * Destroy session
     * 
     * @param string $msg optional
     *
     * @return RedirectResponse
     */
    public function logout($msg = 'logout')
    {
        Session::destroy();

        if ($msg) {
            return $this->redirect("/?msg={$msg}");
        }

        return $this->redirect('/');
    }
}
