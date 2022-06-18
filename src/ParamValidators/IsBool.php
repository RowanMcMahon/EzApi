<?php

namespace EzApi\ParamValidators;

use EzApi\Contracts\ParamValidator;

class IsBool extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_bool($value);
    }
}
