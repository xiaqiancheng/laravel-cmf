<?php

namespace XADMIN\LaravelCmf\Events;

use Illuminate\Queue\SerializesModels;

class RoutingAfter
{
    use SerializesModels;

    public $router;

    public function __construct()
    {
        $this->router = app('router');

        // @deprecate
        //
        event('laravel-cmf.routing.after', $this->router);
    }
}
