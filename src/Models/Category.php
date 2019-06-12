<?php

namespace XADMIN\LaravelCmf\Models;

use Illuminate\Database\Eloquent\Model;
use XADMIN\LaravelCmf\Facades\LaravelCmf;
use XADMIN\LaravelCmf\Traits\Translatable;

class Category extends Model
{
    use Translatable;

    protected $translatable = ['slug', 'name'];

    protected $table = 'categories';

    protected $fillable = ['slug', 'name'];

    public function posts()
    {
        return $this->hasMany(LaravelCmf::modelClass('Post'))
            ->published()
            ->orderBy('created_at', 'DESC');
    }

    public function parentId()
    {
        return $this->belongsTo(self::class);
    }
}
