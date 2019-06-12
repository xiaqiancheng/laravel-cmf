<?php

namespace XADMIN\LaravelCmf\Actions;

class EditAction extends AbstractAction
{
    public function getTitle()
    {
        return __('laravel-cmf::generic.edit');
    }

    public function getIcon()
    {
        return 'laravel-cmf-edit';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right edit',
        ];
    }

    public function getDefaultRoute()
    {
        return route('laravel-cmf.'.$this->dataType->slug.'.edit', $this->data->{$this->data->getKeyName()});
    }
}
