<?php

namespace EzApi\ParamValidators;

use EzApi\Contracts\ParamValidator;

class IsNumeric extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_numeric($value);
    }
}
