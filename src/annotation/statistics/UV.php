<?php

namespace Luoyue\WebmanMvcCore\annotation\statistics;

#[\Attribute(\Attribute::TARGET_METHOD)]
class UV
{

    public function __construct(string $callback)
    {

    }

}