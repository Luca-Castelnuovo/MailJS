<?php

namespace App\Controllers;

use DB;
use Exception;
use App\Helpers\MailHelper;
use App\Helpers\SessionHelper;
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
    public function submit(ServerRequest $request)
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
        ], ['uuid' => $request->uuid]);

        $email_content = $this->renderFromText(
            $template['email_content'],
            (array) $request->data
        );

        try {
            if ($template['captcha_key']) {
                if (!CaptchaHelper::validate(
                    $request->data->{"g-recaptcha-response"},
                    $template['captcha_key']
                )) {
                    throw new Exception('Invalid captcha response');
                }
            }

            MailHelper::send([
                'email_to' => $template['email_to'],
                'email_replyTo' => $template['email_replyTo'],
                'email_cc' => $template['email_cc'],
                'email_bcc' => $template['email_bcc'],
                'email_fromName' => $template['email_fromName'],
                'email_subject' => $template['email_subject'],
                'email_content' => $email_content
            ]);
        } catch (Exception $e) {
            return $this->respondJsonError('Mail Error', $e->getMessage(), 400);
        }

        DB::create(
            'history',
            [
                'template_id' => $template['id'],
                'template_params' => json_encode($request->data),
                'user_ip' => $_SERVER['REMOTE_ADDR'],
                'origin' => $request->origin ?: 'unknown',
            ]
        );

        return $this->respondJson($request->data);
    }

    /**
     * Get history connected to template_id
     *
     * @param string $template_id
     * 
     * @return JsonResponse
     */
    public function history($template_id) // TODO: remove
    {
        if (!$this->hasUserTemplate($template_id, SessionHelper::get('user_id'))) {
            return $this->respondJsonError(
                'template_not_owned',
                'The user doesn\'t own the template',
                403
            );
        }

        $history = DB::select('history', [
            'user_ip',
            'origin',
            'created_at'
        ], [
            'template_id' => $template_id
        ]);

        return $this->respondJson($history);
    }
}
