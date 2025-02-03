<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\cache\parser\CachePutParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class CachePut extends AbstractAnnotationAttribute
{

    /**
     * 方法无异常，则使用方法返回值放入到缓存中
     * @param string $name
     * @param string $key
     * @param int|null $expire
     * @param string $driver
     */
    public function __construct(string $name = '', string $key = '', ?int $expire = null, string $driver = '')
    {
        $this->setArguments(func_get_args());
    }

    public static function getParser(): array|string
    {
        return CachePutParser::class;
    }

}