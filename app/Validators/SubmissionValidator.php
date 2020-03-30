<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class SubmissionValidator extends ValidatorBase
{
    // TODO: create Validators

    /**
     * Validate form submission
     *
     * @param object $data
     *
     * @return void
     */
    public static function form($data)
    {
        $v = v::attribute('redirect_to', v::stringType()->length(1, 32))
            ->attribute('birthdate', v::date()->age(18));

        // redirect_to

        SubmissionValidator::validate($v, $data);
    }
}
