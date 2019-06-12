<?php

namespace XADMIN\LaravelCmf\Alert\Components;

class TitleComponent extends AbstractComponent
{
    protected $title;

    public function create($title)
    {
        $this->title = $title;
    }

    public function render()
    {
        return "<strong>{$this->title}</strong>";
    }
}
