<?php

namespace XADMIN\LaravelCmf\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class PolygonType extends Type
{
    const NAME = 'polygon';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'polygon';
    }
}
