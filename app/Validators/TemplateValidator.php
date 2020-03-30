<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class TemplateValidator extends ValidatorBase
{
    /**
     * Validate json submission
     *
     * @param object $data
     *
     * @return void
     */
    public static function create($data)
    {
        $v = v::attribute('name', v::alnum()->length(1, 64))
            ->attribute('captcha_key', v::alnum()->length(0, 64))
            ->attribute('email_to', v::stringType()->length(1, 256))
            ->attribute('email_replyTo', v::stringType()->length(0, 256))
            ->attribute('email_cc', v::stringType()->length(0, 256))
            ->attribute('email_bcc', v::stringType()->length(0, 256))
            ->attribute('email_fromName', v::stringType()->length(0, 256))
            ->attribute('email_subject', v::stringType()->length(1, 256));

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
        $v = v::attribute('name', v::alnum()->length(0, 64))
            ->attribute('captcha_key', v::alnum()->length(0, 64))
            ->attribute('email_to', v::stringType()->length(0, 256))
            ->attribute('email_replyTo', v::stringType()->length(0, 256))
            ->attribute('email_cc', v::stringType()->length(0, 256))
            ->attribute('email_bcc', v::stringType()->length(0, 256))
            ->attribute('email_fromName', v::stringType()->length(0, 256))
            ->attribute('email_subject', v::stringType()->length(0, 256));
        // ->attribute('email_content', v::stringType());

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
        $v = v::attribute('allowed_origin', v::url()->length(1, 256));

        SubmissionValidator::validate($v, $data);
    }
}
