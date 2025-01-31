<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

#[\Attribute(\Attribute::TARGET_METHOD)]
class CacheUpdate
{

    public function __construct(string $name = '', string $key = '', string $expire = '', string $driver = '')
    {
    }

}