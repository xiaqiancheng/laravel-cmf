<?php

namespace XADMIN\LaravelCmf\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class LineStringType extends Type
{
    const NAME = 'linestring';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'linestring';
    }
}
