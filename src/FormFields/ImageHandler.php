<?php

namespace XADMIN\LaravelCmf\FormFields;

class ImageHandler extends AbstractHandler
{
    protected $codename = 'image';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('laravel-cmf::formfields.image', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
