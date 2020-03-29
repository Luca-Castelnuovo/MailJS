<?php

namespace App\Controllers;

use DB;
use Exception;
use App\Helpers\CaptchaHelper;
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
                $request->data,
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
                $request->data,
                $request->getHeader('origin')
            );
        } catch (Exception $e) {
            return $this->redirect("{$redirect_to}?error={$e}", 400);
        }

        return $this->redirect($redirect_to);
    }

    /**
     * Handle all logic
     *
     * @param string $uuid
     * @param array $data
     * @param string $origin_header
     * 
     * @return void
     */
    private function sendMail($uuid, $data, $origin_header = null)
    {
        $template = DB::get('templates', [
            'id',
            'captcha_key',
            'email_to',
            'email_replyTo',
            'email_cc',
            'email_bcc',
            'email_fromName',
            'email_subject',
            'email_content'
        ], ['uuid' => $uuid]);

        if ($template['captcha_key']) {
            if (!CaptchaHelper::validate(
                $data['g-recaptcha-response'],
                $template['captcha_key']
            )) {
                throw new Exception('Invalid captcha response');
            }
        }

        // TODO: build template
        // $data
        // TODO: send email

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
