<?php

namespace XADMIN\LaravelCmf\Actions;

class DeleteAction extends AbstractAction
{
    public function getTitle()
    {
        return __('laravel-cmf::generic.delete');
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
            'class'   => 'btn btn-sm btn-danger pull-right delete',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id'      => 'delete-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return 'javascript:;';
    }

    public function shouldActionDisplayOnDataType()
    {
        $model = $this->data->getModel();
        if ($model && in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses($model)) && $this->data->deleted_at) {
            return false;
        }

        return parent::shouldActionDisplayOnDataType();
    }
}
