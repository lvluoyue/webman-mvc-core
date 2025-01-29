<?php

namespace Luoyue\WebmanMvcCore\annotation\authorization;

#[\Attribute(\Attribute::TARGET_METHOD)]
class hasRole
{


    public function __construct(string $role)
    {
        if (empty($role)) {
            throw new \InvalidArgumentException('permissions is empty');
        }
    }

}