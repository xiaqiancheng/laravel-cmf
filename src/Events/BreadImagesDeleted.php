<?php

namespace XADMIN\LaravelCmf\Events;

use Illuminate\Queue\SerializesModels;

class BreadImagesDeleted
{
    use SerializesModels;

    public $data;

    public $images;

    public function __construct($data, $images)
    {
        $this->data = $data;

        $this->images = $images;
    }
}
