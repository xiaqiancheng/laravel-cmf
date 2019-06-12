<?php

namespace XADMIN\LaravelCmf\Database\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class JsonType extends Type
{
    const NAME = 'json';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'json';
    }
}
