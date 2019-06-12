<?php

namespace XADMIN\LaravelCmf\Database\Types\Postgresql;

use XADMIN\LaravelCmf\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    const NAME = 'character varying';
    const DBTYPE = 'varchar';
}
