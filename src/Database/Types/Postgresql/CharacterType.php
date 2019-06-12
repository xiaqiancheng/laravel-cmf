<?php

namespace XADMIN\LaravelCmf\Database\Types\Postgresql;

use XADMIN\LaravelCmf\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
