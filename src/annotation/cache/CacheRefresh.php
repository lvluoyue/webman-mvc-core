<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

#[\Attribute(\Attribute::TARGET_METHOD)]
class CacheRefresh
{

    /**
     * 定时刷新缓存（未实现）
     * @param int $refresh
     */
    public function __construct(int $refresh)
    {
    }

}