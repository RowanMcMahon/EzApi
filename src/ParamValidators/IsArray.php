<?php

namespace EzApi\ParamValidators;

use EzApi\Contracts\ParamValidator;

class IsArray extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_array($value);
    }
}
