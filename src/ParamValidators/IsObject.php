<?php

namespace App\Services\EzApi\ParamValidators;

use Contracts\ParamValidator;

class IsObject extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_object($value);
    }
}
