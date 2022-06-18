<?php

namespace EzApi\ParamValidators;

use EzApi\Contracts\ParamValidator;

class IsObject extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_object($value);
    }
}
