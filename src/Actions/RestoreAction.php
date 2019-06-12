<?php

namespace XADMIN\LaravelCmf\Actions;

class RestoreAction extends AbstractAction
{
    public function getTitle()
    {
        return __('laravel-cmf::generic.restore');
    }

    public function getIcon()
    {
        return 'laravel-cmf-trash';
    }

    public function getPolicy()
    {
        return 'delete';
    }

    public function getAttributes()
    {
        return [
            'class'   => 'btn btn-sm btn-success pull-right restore',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id'      => 'restore-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return route('laravel-cmf.'.$this->dataType->slug.'.restore', $this->data->{$this->data->getKeyName()});
    }

    public function shouldActionDisplayOnDataType()
    {
        $model = $this->data->getModel();
        if (!($model && in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses($model)) && $this->data->deleted_at)) {
            return false;
        }

        return parent::shouldActionDisplayOnDataType();
    }
}
