<?php

namespace XADMIN\LaravelCmf\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class TsQueryType extends Type
{
    const NAME = 'tsquery';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tsquery';
    }
}
