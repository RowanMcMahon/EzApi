<?php

namespace EzApi\ParamValidators;

use EzApi\Contracts\ParamValidator;

class IsString extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_string($value);
    }
}
