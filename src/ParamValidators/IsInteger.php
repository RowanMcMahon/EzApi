<?php

namespace EzApi\ParamValidators;

use EzApi\Contracts\ParamValidator;

class IsInteger extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_integer($value);
    }
}
