<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class TemplateValidator extends ValidatorBase
{
    // TODO: create Validators

    /**
     * Validate json submission
     *
     * @param object $data
     *
     * @return void
     */
    public static function create($data)
    {
        $v = v::attribute('name', v::stringType()->length(1, 32))
            ->attribute('birthdate', v::date()->age(18));

        // [
        //     'name' => $request->data->name,
        //     'captcha_key' => $request->data->captcha_key,
        //     'email_to' => $request->data->email_to,
        //     'email_replyTo' => $request->data->email_replyTo,
        //     'email_cc' => $request->data->email_cc,
        //     'email_bcc' => $request->data->email_bcc,
        //     'email_fromName' => $request->data->email_fromName,
        //     'email_subject' => $request->data->email_subject
        // ]

        SubmissionValidator::validate($v, $data);
    }

    /**
     * Validate json submission
     *
     * @param object $data
     *
     * @return void
     */
    public static function update($data)
    {
        $v = v::attribute('name', v::stringType()->length(1, 32))
            ->attribute('birthdate', v::date()->age(18));

        // [
        //     'name' => $request->data->name ?: $template['name'],
        //     'captcha_key' => $request->data->captcha_key ?: $template['captcha_key'],
        //     'email_to' => $request->data->email_to ?: $template['email_to'],
        //     'email_replyTo' => $request->data->email_replyTo ?: $template['email_replyTo'],
        //     'email_cc' => $request->data->email_cc ?: $template['email_cc'],
        //     'email_bcc' => $request->data->email_bcc ?: $template['email_bcc'],
        //     'email_fromName' => $request->data->email_fromName ?: $template['email_fromName'],
        //     'email_subject' => $request->data->email_subject ?: $template['email_subject'],
        //     // 'email_content' => $request->data->email_content ?: $template['email_content'],
        // ]

        SubmissionValidator::validate($v, $data);
    }

    /**
     * Validate json submission
     *
     * @param object $data
     *
     * @return void
     */
    public static function createKey($data)
    {
        $v = v::attribute('name', v::stringType()->length(1, 32))
            ->attribute('birthdate', v::date()->age(18));

        // 'allowed_origin' => $request->data->allowed_origin

        SubmissionValidator::validate($v, $data);
    }
}
