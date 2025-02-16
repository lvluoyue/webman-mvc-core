<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\cache\parser\CachedParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Cached extends AbstractAnnotationAttribute
{

    /**
     * 缓存方法返回值并尝试获取缓存
     * @param string $name 缓存名称
     * @param string $key 缓存key
     * @param ?int $expire 过期时间，默认为env.CACHE_EXPIRE_DEFAULT
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