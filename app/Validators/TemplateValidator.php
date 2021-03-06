<?php

namespace App\Validators;

use CQ\Validators\Validator;
use Respect\Validation\Validator as v;

class TemplateValidator extends Validator
{
    /**
     * Validate json submission.
     *
     * @param object $data
     */
    public static function create($data)
    {
        $v = v::attribute('name', v::alnum(' ', '-')->length(1, 64))
            ->attribute('captcha_key', v::optional(v::alnum()->length(32, 64)))
            ->attribute('email_to', v::stringType()->length(1, 256))
            ->attribute('email_replyTo', v::optional(v::stringType()->length(1, 256)))
            ->attribute('email_cc', v::optional(v::stringType()->length(1, 256)))
            ->attribute('email_bcc', v::optional(v::stringType()->length(1, 256)))
            ->attribute('email_fromName', v::optional(v::stringType()->length(1, 256)))
            ->attribute('email_subject', v::stringType()->length(1, 256))
            ->attribute('email_content', v::stringType()->length(1, 15000))
        ;

        self::validate($v, $data);
    }

    /**
     * Validate json submission.
     *
     * @param object $data
     */
    public static function update($data)
    {
        $v = v::attribute('name', v::alnum(' ', '-')->length(1, 64))
            ->attribute('captcha_key', v::optional(v::alnum()->length(32, 64)))
            ->attribute('email_to', v::stringType()->length(1, 256))
            ->attribute('email_replyTo', v::optional(v::stringType()->length(1, 256)))
            ->attribute('email_cc', v::optional(v::stringType()->length(1, 256)))
            ->attribute('email_bcc', v::optional(v::stringType()->length(1, 256)))
            ->attribute('email_fromName', v::optional(v::stringType()->length(1, 256)))
            ->attribute('email_subject', v::stringType()->length(1, 256))
            ->attribute('email_content', v::stringType()->length(1, 15000))
        ;

        self::validate($v, $data);
    }

    /**
     * Validate json submission.
     *
     * @param object $data
     */
    public static function createKey($data)
    {
        $v = v::attribute('allowed_origin', v::url()->length(1, 256));

        self::validate($v, $data);
    }
}
