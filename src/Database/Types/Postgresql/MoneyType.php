<?php

namespace XADMIN\LaravelCmf\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use XADMIN\LaravelCmf\Database\Types\Type;

class MoneyType extends Type
{
    const NAME = 'money';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'money';
    }
}
