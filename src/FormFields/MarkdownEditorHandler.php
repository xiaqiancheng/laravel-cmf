<?php

namespace XADMIN\LaravelCmf\FormFields;

class MarkdownEditorHandler extends AbstractHandler
{
    protected $codename = 'markdown_editor';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('laravel-cmf::formfields.markdown_editor', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
