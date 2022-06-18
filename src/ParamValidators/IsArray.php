<?php

namespace App\Services\EzApi\ParamValidators;

use Contracts\ParamValidator;

class IsArray extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_array($value);
    }
}
