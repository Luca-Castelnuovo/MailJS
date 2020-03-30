<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class SubmissionValidator extends ValidatorBase
{
    /**
     * Validate form submission
     *
     * @param object $data
     *
     * @return void
     */
    public static function form($data)
    {
        $v = v::attribute('redirect_to', v::url()->length(0, 256));

        SubmissionValidator::validate($v, $data);
    }
}
