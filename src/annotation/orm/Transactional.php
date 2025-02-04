<?php

namespace Luoyue\WebmanMvcCore\annotation\orm;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\orm\parser\TransactionalParser;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Transactional extends AbstractAnnotationAttribute
{

    public static function getParser(): array|string
    {
        return TransactionalParser::class;
    }

}