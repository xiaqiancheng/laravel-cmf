<?php

namespace XADMIN\LaravelCmf\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class BlobType extends Type
{
    const NAME = 'blob';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'blob';
    }
}
