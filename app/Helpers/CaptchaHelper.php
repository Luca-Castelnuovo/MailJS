<?php

namespace App\Helpers;

use ReCaptcha\ReCaptcha;

class CaptchaHelper
{
    /**
     * Validates the captcha response.
     *
     * @param string $captcha_response
     * @param string $captcha_secret
     *
     * @return bool
     */
    public static function validate($captcha_response, $captcha_secret)
    {
        $recaptcha = new ReCaptcha($captcha_secret);
        $response = $recaptcha->verify($captcha_response);

        return $response->isSuccess();
    }
}
