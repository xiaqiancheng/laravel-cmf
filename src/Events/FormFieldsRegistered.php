<?php

namespace XADMIN\LaravelCmf\Events;

use Illuminate\Queue\SerializesModels;

class FormFieldsRegistered
{
    use SerializesModels;

    public $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;

        // @deprecate
        //
        event('laravel-cmf.form-fields.registered', $fields);
    }
}
