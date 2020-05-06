<?php

namespace App\Controllers;

use DB;
use App\Helpers\SessionHelper;

class UserController extends Controller
{
    /**
     * Dashboard screen
     *
     * @return HtmlResponse
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
                'user_id' => SessionHelper::get('user_id'),
                "ORDER" => ["id" => "ASC"]
            ]
        );

        $result = [];

        foreach ($templates as $template) {
            $history = DB::select(
                'history',
                [
                    'template_params[JSON]',
                    'user_ip',
                    'origin',
                    'created_at'
                ],
                [
                    'template_id' => $template['id'],
                    "ORDER" => ["id" => "ASC"]
                ]
            );

            $result[$template['id']] = $template;
            $result[$template['id']]['history'] = $history;
        }

        $templates = array_values($result);

        return $this->respond('dashboard.twig', [
            'templates' => $templates
        ]);
    }
}
