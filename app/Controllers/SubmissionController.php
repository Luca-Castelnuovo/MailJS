<?php

namespace App\Controllers;

use DB;
use Exception;
use Zend\Diactoros\ServerRequest;

class SubmissionController extends Controller
{
    /**
     * Handle submission in JSON format.
     *
     * @param ServerRequest $request
     * 
     * @return JsonResponse
     */
    public function json(ServerRequest $request)
    {
        // TODO: validate parameters

        try {
            $this->sendMail(
                $request->uuid,
                $request->getHeader('origin')
            );
        } catch (Exception $e) {
            return $this->respondJsonError('Mail Error', $e, 400);
        }

        return $this->respondJson();
    }

    /**
     * Handle submission in JSON format.
     *
     * @param ServerRequest $request
     * 
     * @return RedirectResponse
     */
    public function form(ServerRequest $request)
    {
        // TODO: validate parameters

        $redirect_to = $request->data['redirect_to']; //var required

        try {
            $this->sendMail(
                $request->uuid,
                $request->getHeader('origin')
            );
        } catch (Exception $e) {
            return $this->redirect($redirect_to, 400);

            return $this->respondJsonError('Mail Error', $e, 400);
        }

        return $this->respondJson();

        // redirect, if error add error to header
        return $this->redirect($redirect_to);
    }

    /**
     * Handle all logic
     *
     * @param string $uuid
     * @param string $origin_header
     * 
     * @return void
     */
    private function sendMail($uuid, $origin_header = null)
    {
        $template = DB::get('templates', [
            'id',
            'email_to',
            'email_replyTo',
            'email_cc',
            'email_bcc',
            'email_fromName',
            'email_subject',
            'email_content'
        ], ['uuid' => $uuid]);

        // build template
        // send email

        DB::create(
            'history',
            [
                'template_id' => $template['id'],
                'user_ip' => $_SERVER['REMOTE_ADDR'],
                'origin' => $origin_header ?: 'unknown',
            ]
        );

        if (false) {
            throw new Exception('Mail server connection not allowed');
        }
    }
}
