<?php

namespace App\Controllers;

use Exception;
use CQ\DB\DB;
use CQ\Response\Twig;
use CQ\Helpers\Session;
use CQ\Helpers\Variant;
use CQ\Captcha\reCaptcha;
use CQ\Controllers\Controller;
use App\Helpers\MailHelper;

class SubmissionController extends Controller
{
    /**
     * Handle submission in JSON format.
     *
     * @param object $request
     * 
     * @return JsonResponse
     */
    public function submit($request)
    {
        $template = DB::get('templates', [
            'id',
            'user_id',
            'user_variant',
            'captcha_key',
            'email_to',
            'email_replyTo',
            'email_cc',
            'email_bcc',
            'email_fromName',
            'email_subject',
            'email_content'
        ], ['id' => $request->id]);

        // TODO: debug
        $variant_provider = new Variant([
            'user' => $template['user_variant'],
            'type' => 'monthly_requests',
            'current_value' => DB::count('history', ['template_owner' => $template['user_id']])
        ]);
        if (!$variant_provider->limitReached()) {
            return $this->respondJson(
                "Monthly request quota reached, max {$variant_provider->configuredValue()}",
                [],
                400
            );
        }

        try {
            if ($template['captcha_key']) {
                if (!reCaptcha::v2(
                    $template['captcha_key'],
                    $request->data->{"g-recaptcha-response"}
                )) {
                    throw new Exception('Invalid captcha response');
                }
            }

            MailHelper::send([
                'email_to' => Twig::renderFromText($template['email_to'], (array) $request->data),
                'email_replyTo' => Twig::renderFromText($template['email_replyTo'], (array) $request->data),
                'email_cc' => Twig::renderFromText($template['email_cc'], (array) $request->data),
                'email_bcc' => Twig::renderFromText($template['email_bcc'], (array) $request->data),
                'email_fromName' => Twig::renderFromText($template['email_fromName'], (array) $request->data),
                'email_subject' => Twig::renderFromText($template['email_subject'], (array) $request->data),
                'email_content' => Twig::renderFromText($template['email_content'], (array) $request->data)
            ]);
        } catch (Exception $e) {
            return $this->respondJson(
                'Mail Error',
                $e->getMessage(),
                400
            );
        }

        DB::create(
            'history',
            [
                'template_id' => $template['id'],
                'template_owner' => $template['user_id'],
                'template_params' => json_encode($request->data),
                'user_ip' => $_SERVER['REMOTE_ADDR'],
                'origin' => $request->origin ?: 'unknown',
            ]
        );

        return $this->respondJson(
            'Submission Success',
            $request->data
        );
    }
}
