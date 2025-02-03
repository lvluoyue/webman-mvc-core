<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\cache\parser\CacheInvalidateParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class CacheInvalidate extends AbstractAnnotationAttribute
{

    /**
     * 方法无异常，则删除缓存
     * @param string $name 缓存名称
     * @param string $key 缓存key
     * @param string $driver 缓存驱动
     */
    public function __construct(string $name = '', string $key = '', string $driver = '')
    {
        $this->setArguments(func_get_args());
    }

    public static function getParser(): array|string
    {
        return CacheInvalidateParser::class;
    }

}