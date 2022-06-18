<?php

require_once __DIR__ . '/vendor/autoload.php';

abstract class EzApi
{
    protected $actions = [];

    public function __call($function, $args)
    {
        if (array_key_exists($function, $this->actions))
        {
            $action = $this->actions[$function];
            $action = new $action($args);
            return $action->handle();
        }

        return $function($args);
    }
}
