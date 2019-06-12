<?php

namespace XADMIN\LaravelCmf\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class FloatType extends Type
{
    const NAME = 'float';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'float';
    }
}
