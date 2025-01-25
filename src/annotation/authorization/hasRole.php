<?php

namespace Luoyue\WebmanMvcCore\annotation\authorization;

#[\Attribute(\Attribute::TARGET_METHOD)]
class hasRole
{


    public function __construct(public string $role)
    {
    }

}