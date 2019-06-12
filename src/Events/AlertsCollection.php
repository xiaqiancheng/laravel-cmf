<?php

namespace XADMIN\LaravelCmf\Events;

use Illuminate\Queue\SerializesModels;

class AlertsCollection
{
    use SerializesModels;

    public $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;

        // @deprecate
        //
        event('laravel-cmf.alerts.collecting', $collection);
    }
}
