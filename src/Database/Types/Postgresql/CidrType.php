<?php

namespace XADMIN\LaravelCmf\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class CidrType extends Type
{
    const NAME = 'cidr';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'cidr';
    }
}
