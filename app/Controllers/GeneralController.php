<?php

namespace App\Controllers;

use DB;
use Exception;
use SplObjectStorage;
use App\Helpers\JWTHelper;
use App\Helpers\SessionHelper;
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

        if ($msg) {
            try {
                $claims = JWTHelper::valid('message', $msg);
                $msg = $claims->message;
            } catch (Exception $e) {
                $msg = '';
            }
        }

        return $this->respond('index.twig', [
            'message' => $msg,
            'logged_in' => SessionHelper::valid()
        ]);
    }

    /**
     * Docs screen
     * 
     * @return RedirectResponse
     */
    public function docs()
    {
        return $this->redirect(config('links.docs'));
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
                break;
        }

        return $this->respond('error.twig', [
            'code' => $code,
            'short_message' => $short_message,
            'message' => $message
        ], $code);
    }

    /**
     * Dashboard screen
     *
     * @return HtmlResponse
     */
    public function dashboard()
    {
        $templates = DB::join(
            'templates',
            [
                'templates.id',
                'templates.name',
                'templates.captcha_key',
                'templates.email_to',
                'templates.email_replyTo',
                'templates.email_cc',
                'templates.email_bcc',
                'templates.email_fromName',
                'templates.email_subject',
                'templates.email_content',
                'templates.updated_at',
                'templates.created_at',

                'history' => [
                    'history.created_at',
                    'history.origin',
                    'history.user_ip',
                    'history.template_params[JSON]'
                ]
            ],
            [
                'user_id' => SessionHelper::get('user_id'),
                "ORDER" => ["templates.id" => "ASC"]
            ],
            [
                "[>]history" => ["id" => "template_id"],
            ]
        );

        $result = [];

        foreach ($templates as $template) {
            $key = $template['id'];

            if (isset($result[$key])) {
                $result[$key]['history'] = [$result[$key]['history'], $template['history']];
            } else {
                $result[$key] = $template;
            }
        }

        $templates = array_values($result);

        return $this->respond('dashboard.twig', [
            'templates' => $templates
        ]);
    }
}
