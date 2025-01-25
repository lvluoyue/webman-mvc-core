<?php

namespace Luoyue\WebmanMvcCore\annotation\authorization;

#[\Attribute(\Attribute::TARGET_METHOD)]
class hasPermi
{

    public array $permissions;

    public function __construct(string $permissions)
    {
        $this->permissions = explode(':', $permissions);
    }

}