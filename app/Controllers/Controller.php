<?php

namespace App\Controllers;

use DB;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class Controller
{
    private $twig;

    /**
     * Provide access for child classes
     * 
     * @return void
     */
    public function __construct()
    {
        // Start twig engine
        $loader = new FilesystemLoader('../views');
        $this->twig = new Environment($loader /* , ['cache' => '../storage/views'] */);
        $this->twig->addGlobal('analytics', config('analytics'));
    }

    /**
     * Get input data
     * 
     * @param array $data
     * @param string $variable
     * @param mixed $fallback optional
     * 
     * @return mixed
     */
    protected function get($data, $variable, $fallback = '')
    {
        return $data[$variable] ?: $fallback;
    }

    /**
     * Shorthand HTML response function
     *
     * @param string $view
     * @param array $parameters
     * @param integer $code optional
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
     * Shorthand JSON response function
     * 
     * @param array $data
     * @param integer $code optional
     * 
     * @return JsonResponse
     */
    protected function respondJson($data = [], $code = 200)
    {
        return new JsonResponse([
            'success' => true,
            'data' => $data
        ], $code);
    }

    /**
     * Shorthand JSON error response function
     *
     * @param string $title
     * @param string $detail optional
     * @param integer $code optional
     * 
     * @return JsonResponse
     */
    protected function respondJsonError($title, $detail = null, $code = 400)
    {
        return new JsonResponse([
            'success' => false,
            'errors' => [
                'status' => $code,
                'title' => $title,
                'detail' => $detail

            ]
        ], $code);
    }

    /**
     * Query all user templates
     *
     * @param int $user_id
     * 
     * @return array
     */
    protected function getUserTemplates($user_id)
    {
        return DB::select(
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
                'user_id' => $user_id
            ]
        );
    }

    /**
     * Check if user owns template
     *
     * @param int $template_id
     * @param string $user_id
     * 
     * @return boolean
     */
    protected function hasUserTemplate($template_id, $user_id)
    {
        return DB::has('templates', [
            'id' => $template_id,
            'user_id' => $user_id
        ]);
    }
}