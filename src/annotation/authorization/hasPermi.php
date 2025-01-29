<?php

namespace Luoyue\WebmanMvcCore\annotation\authorization;

#[\Attribute(\Attribute::TARGET_METHOD)]
class hasPermi
{

    public function __construct(string $permissions)
    {
        if (empty($permissions)) {
            throw new \InvalidArgumentException('permissions is empty');
        }
    }

}