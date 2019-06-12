<?php

namespace XADMIN\LaravelCmf\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class PointType extends Type
{
    const NAME = 'point';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'point';
    }
}
