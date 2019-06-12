<?php

namespace XADMIN\LaravelCmf\Events;

use Illuminate\Queue\SerializesModels;
use XADMIN\LaravelCmf\Models\Setting;

class SettingUpdated
{
    use SerializesModels;

    public $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
