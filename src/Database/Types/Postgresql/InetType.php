<?php

namespace XADMIN\LaravelCmf\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class InetType extends Type
{
    const NAME = 'inet';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'inet';
    }
}
