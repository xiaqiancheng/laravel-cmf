<?php

namespace XADMIN\LaravelCmf\FormFields;

class MultipleImagesHandler extends AbstractHandler
{
    protected $codename = 'multiple_images';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('laravel-cmf::formfields.multiple_images', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
