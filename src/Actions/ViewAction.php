<?php

namespace XADMIN\LaravelCmf\Actions;

class ViewAction extends AbstractAction
{
    public function getTitle()
    {
        return __('laravel-cmf::generic.view');
    }

    public function getIcon()
    {
        return 'laravel-cmf-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-warning pull-right view',
        ];
    }

    public function getDefaultRoute()
    {
        return route('laravel-cmf.'.$this->dataType->slug.'.show', $this->data->{$this->data->getKeyName()});
    }
}
