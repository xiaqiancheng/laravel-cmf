<?php

namespace XADMIN\LaravelCmf\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use XADMIN\LaravelCmf\Contracts\User as UserContract;
use XADMIN\LaravelCmf\Traits\LaravelCmfUser;

class User extends Authenticatable implements UserContract
{
    use LaravelCmfUser;

    protected $guarded = [];

    public $additional_attributes = ['locale'];

    public function getAvatarAttribute($value)
    {
        return $value ?? config('laravel-cmf.user.default_avatar', 'users/default.png');
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setSettingsAttribute($value)
    {
        $this->attributes['settings'] = $value->toJson();
    }

    public function getSettingsAttribute($value)
    {
        return collect(json_decode($value));
    }

    public function setLocaleAttribute($value)
    {
        $this->settings = $this->settings->merge(['locale' => $value]);
    }

    public function getLocaleAttribute()
    {
        return $this->settings->get('locale');
    }
}
