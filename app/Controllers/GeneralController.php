<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use Zend\Diactoros\ServerRequest;

class GeneralController extends Controller
{
    /**
     * Login screen
     * 
     * @return HtmlResponse
     */
    public function index(ServerRequest $request)
    {
        $msg = $request->getQueryParams()['msg'] ?: '';
        $code = $request->getQueryParams()['code'] ?: '';

        if ($code) {
            return $this->redirect("/auth/callback?code={$code}");
        }

        if ($msg) {
            switch ($msg) {
                case 'logout':
                    $msg = 'You have been logged out!';
                    break;

                case 'token':
                    $msg = 'Invalid authentication!';
                    break;

                default:
                    $msg = '';
                    break;
            }
        }

        return $this->respond('index.twig', [
            'message' => $msg,
            'logged_in' => AuthHelper::valid()
        ]);
    }

    /**
     * Error screen
     * 
     * @param string $code
     *
     * @return HtmlResponse
     */
    public function error($code)
    {
        switch ($code) {
            case '403':
                $short_message = 'Oops! Access denied';
                $message = 'Access to this page is forbidden';
                break;
            case '404':
                $short_message = 'Oops! Page not found';
                $message = 'We are sorry, but the page you requested was not found';
                break;
            case '422':
                $short_message = 'Oops! Parameters missing';
                $message = 'The page you requested missed required parameters';
                break;
            case '500':
                $short_message = 'Oops! Server error';
                $message = 'We are experiencing some technical issues';
                break;
            case '502':
                $short_message = 'Oops! Proxy error';
                $message = 'We are experiencing some technical issues';
                break;

            default:
                $short_message = 'Oops! Unknown Error';
                $message = 'Unknown error occured';
                $code = 500;
                break;
        }

        return $this->respond('error.twig', [
            'code' => $code,
            'short_message' => $short_message,
            'message' => $message
        ], $code);
    }
}
