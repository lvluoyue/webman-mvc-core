<?php

namespace Luoyue\WebmanMvcCore\annotation\orm;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\orm\parser\TransactionalParser;
use Throwable;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Transactional extends AbstractAnnotationAttribute
{

    /**
     * 数据库事务注解
     * @param string|array $rollbackFor 回滚的异常
     */
    public function __construct(string|array $rollbackFor = Throwable::class)
    {
        $this->setArguments(func_get_args());
    }

    public static function getParser(): array|string
    {
        return TransactionalParser::class;
    }

}