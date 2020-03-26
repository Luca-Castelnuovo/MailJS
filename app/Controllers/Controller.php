<?php

namespace App\Controllers;

use DB;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Zend\Diactoros\Response\HtmlResponse;

class Controller
{
    private $twig;

    /**
     * Enable access for child classes
     * 
     * @return void
     */
    public function __construct()
    {
        // Start template engine
        $loader = new FilesystemLoader('../views');
        $this->twig = new Environment($loader);
        // $this->twig = new Environment($loader, ['cache' => '../storage/views']);
        $this->twig->addGlobal('analytics', config('analytics'));
    }

    /**
     * Shorthand response function
     *
     * @param string $view
     * @param array $parameters
     * @param integer $code
     * 
     * @return HtmlResponse
     */
    protected function respond($view, $parameters = [], $code = 200)
    {
        return new HtmlResponse(
            $this->twig->render(
                $view,
                $parameters
            ),
            $code
        );
    }

    /**
     * Query all user templates
     *
     * @param int $owner_id [optional]
     * 
     * @return void
     */
    protected function getUserTemplates($owner_id = null)
    {
        $owner_id = $owner_id ? $owner_id : $_SESSION['id'];

        $data = DB::select(
            'templates',
            [
                'id',
                'name',
                'captcha_key',
                'email_to',
                'email_replyTo',
                'email_cc',
                'email_bcc',
                'email_fromName',
                'email_subject',
                'email_content',
                'updated_at',
                'created_at'
            ],
            [
                'owner_id' => $owner_id
            ]
        );

        return $data;
    }
}
