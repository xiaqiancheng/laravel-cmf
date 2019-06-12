<?php

namespace XADMIN\LaravelCmf\Models;

use Illuminate\Database\Eloquent\Model;
use XADMIN\LaravelCmf\Events\SettingUpdated;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    public $timestamps = false;

    protected $dispatchesEvents = [
        'updating' => SettingUpdated::class,
    ];
}
