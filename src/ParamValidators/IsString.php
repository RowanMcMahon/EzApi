<?php

namespace App\Services\EzApi\ParamValidators;

use Contracts\ParamValidator;

class IsString extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_string($value);
    }
}
