<?php

namespace XADMIN\LaravelCmf\Traits;

use Illuminate\View\View;

trait Renderable
{
    public function render($content)
    {
        if ($content instanceof View) {
            return $content->render();
        }

        return $content;
    }
}
