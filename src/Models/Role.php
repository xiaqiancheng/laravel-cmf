<?php

namespace XADMIN\LaravelCmf\Models;

use Illuminate\Database\Eloquent\Model;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class Role extends Model
{
    protected $guarded = [];

    public function users()
    {
        $userModel = LaravelCmf::modelClass('User');

        return $this->belongsToMany($userModel, 'user_roles')
                    ->select(app($userModel)->getTable().'.*')
                    ->union($this->hasMany($userModel))->getQuery();
    }

    public function permissions()
    {
        return $this->belongsToMany(LaravelCmf::modelClass('Permission'));
    }
}
