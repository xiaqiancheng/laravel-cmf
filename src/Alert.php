<?php

namespace XADMIN\LaravelCmf;

use XADMIN\LaravelCmf\Alert\Components\ComponentInterface;

class Alert
{
    protected $components;

    protected $name;
    protected $type;

    public function __construct($name, $type = 'default')
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function addComponent(ComponentInterface $component)
    {
        $this->components[] = $component;

        return $this;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __call($name, $arguments)
    {
        $component = app('laravel-cmf.alert.components.'.$name, ['alert' => $this])
            ->setAlert($this);

        call_user_func_array([$component, 'create'], $arguments);

        return $this->addComponent($component);
    }
}
