<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\cache\parser\CachedParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Cached extends AbstractAnnotationAttribute
{

    /**
     * 缓存方法返回值
     * @param string $name 缓存名称
     * @param string|int $key 缓存key
     * @param ?int $expire 过期时间
     * @param string $driver 缓存驱动
     */
    public function __construct(string $name = '', string $key = '', ?int $expire = null, string $driver = '')
    {
        $this->setArguments(func_get_args());
    }

    public static function getParser(): array|string
    {
        return CachedParser::class;
    }

}