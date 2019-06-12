<?php

namespace XADMIN\LaravelCmf\Database\Types\Postgresql;

use XADMIN\LaravelCmf\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    const NAME = 'double precision';
    const DBTYPE = 'float8';
}
