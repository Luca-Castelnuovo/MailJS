<?php

namespace App\Controllers;

use DB;
use Exception;
use App\Helpers\CaptchaHelper;
use App\Helpers\MailHelper;
use App\Validators\SubmissionValidator;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\HtmlResponse;

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
        try {
            $this->sendMail(
                $request->uuid,
                $request->data,
                $request->origin
            );
        } catch (Exception $e) {
            return $this->respondJsonError('Mail Error', $e->getMessage(), 400);
        }

        return $this->respondJson($request->data);
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
        $redirect_to = $request->data->redirect_to ?: $request->referer;

        try {
            SubmissionValidator::form($request->data);
        } catch (Exception $e) {
            return new HtmlResponse($e->getMessage()); // TODO: remove debug
            // return $this->redirect("{$redirect_to}?error={$e->getMessage()}", 422);
        }

        try {
            $this->sendMail(
                $request->uuid,
                $request->data,
                $request->origin
            );
        } catch (Exception $e) {
            return $this->redirect("{$redirect_to}?error={$e->getMessage()}", 400);
        }

        return $this->redirect("{$redirect_to}?success");
    }

    /**
     * Get history connected to template_id
     *
     * @param string $template_id
     * 
     * @return JsonResponse
     */
    public function history($template_id)
    {
        $history = DB::select('history', [
            'user_ip',
            'origin',
            'created_at'
        ], [
            'template_id' => $template_id
        ]);

        return $this->respondJson($history);
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
    private function sendMail($uuid, $data, $origin_header)
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
                $data->{"g-recaptcha-response"},
                $template['captcha_key']
            )) {
                throw new Exception('Invalid captcha response');
            }
        }

        $email_content = $this->renderFromText(
            $template['email_content'],
            (array) $data
        );

        MailHelper::send([
            'email_to' => $template['email_to'],
            'email_replyTo' => $template['email_replyTo'],
            'email_cc' => $template['email_cc'],
            'email_bcc' => $template['email_bcc'],
            'email_fromName' => $template['email_fromName'],
            'email_subject' => $template['email_subject'],
            'email_content' => $email_content
        ]);

        DB::create(
            'history',
            [
                'template_id' => $template['id'],
                'template_params' => json_encode($data),
                'user_ip' => $_SERVER['REMOTE_ADDR'],
                'origin' => $origin_header ?: 'unknown',
            ]
        );
    }
}
