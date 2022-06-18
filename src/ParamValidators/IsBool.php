<?php

namespace App\Services\EzApi\ParamValidators;

use Contracts\ParamValidator;

class IsBool extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_bool($value);
    }
}
