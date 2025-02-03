<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

#[\Attribute(\Attribute::TARGET_METHOD)]
class CacheInvalidate
{

    /**
     * 方法无异常，则删除缓存
     * @param string $name
     * @param string $key
     * @param string $expire
     * @param string $driver
     */
    public function __construct(string $name = '', string $key = '', string $expire = '', string $driver = '')
    {
    }

}