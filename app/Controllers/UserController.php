<?php

namespace App\Controllers;

use CQ\DB\DB;
use CQ\Helpers\Session;
use CQ\Helpers\Variant;
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
                'user_id' => Session::get('id')
            ]
        );

        $variant_provider = new Variant([
            'user' => Session::get('variant'),
            'type' => 'history_access'
        ]);

        return $this->respond('dashboard.twig', [
            'templates' => $templates,
            'history_access' => $variant_provider->configuredValue(),
            'remaining_requests' => $this->remainingRequests()
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
        if (!DB::has('templates', ['id' => $id, 'user_id' => Session::get('id')])) {
            return $this->redirect('/dashboard');
        }

        $variant_provider = new Variant([
            'user' => Session::get('variant'),
            'type' => 'history_access'
        ]);
        if (!$variant_provider->configuredValue()) {
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
                'ORDER' => ['id' => 'DESC']
            ]
        );

        return $this->respond('history.twig', [
            'history' => $history,
            'remaining_requests' => $this->remainingRequests()
        ]);
    }

    /**
     * Get number of remaining requests
     *
     * @return int
     */
    private function remainingRequests()
    {
        $used_requests = DB::count('history', ['template_owner' => Session::get('id')]);
        $variant_provider = new Variant([
            'user' => Session::get('variant'),
            'type' => 'monthly_requests'
        ]);
        $remaining_requests = $variant_provider->configuredValue() - $used_requests;

        return $remaining_requests;
    }
}
