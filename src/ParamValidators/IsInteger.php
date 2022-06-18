<?php

namespace App\Services\EzApi\ParamValidators;

use Contracts\ParamValidator;

class IsInteger extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_integer($value);
    }
}
