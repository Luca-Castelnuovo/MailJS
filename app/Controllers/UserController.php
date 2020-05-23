<?php

namespace App\Controllers;

use CQ\DB\DB;
use CQ\Helpers\Session;
use CQ\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Dashboard screen
     *
     * @return Html
     */
    public function dashboard()
    {
        $templates = DB::select(
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
                'user_id' => Session::get('user_id'),
                "ORDER" => ["id" => "ASC"]
            ]
        );

        return $this->respond('dashboard.twig', [
            'templates' => $templates
        ]);
    }

    /**
     * Template history
     *
     * @param string $id
     * 
     * @return Html
     */
    public function history($id)
    {
        if (!DB::has('templates', ['id' => $id, 'user_id' => Session::get('user_id')])) {
            return $this->redirect('/dashboard');
        }

        $history = DB::select(
            'history',
            [
                'template_params[JSON]',
                'user_ip',
                'origin',
                'created_at'
            ],
            [
                'template_id' => $id,
                "ORDER" => ["id" => "ASC"]
            ]
        );

        return $this->respond('history.twig', [
            'history' => $history
        ]);
    }
}
