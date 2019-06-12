<?php

namespace XADMIN\LaravelCmf\Events;

use Illuminate\Queue\SerializesModels;

class RoutingAdmin
{
    use SerializesModels;

    public $router;

    public function __construct()
    {
        $this->router = app('router');

        // @deprecate
        //
        event('laravel-cmf.admin.routing', $this->router);
    }
}
