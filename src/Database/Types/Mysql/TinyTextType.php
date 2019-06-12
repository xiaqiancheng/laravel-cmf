<?php

namespace XADMIN\LaravelCmf\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class TinyTextType extends Type
{
    const NAME = 'tinytext';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tinytext';
    }
}
