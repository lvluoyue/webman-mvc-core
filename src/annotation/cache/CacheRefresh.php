<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

#[\Attribute(\Attribute::TARGET_METHOD)]
class CacheRefresh
{

    public function __construct(int $refresh)
    {
    }

}