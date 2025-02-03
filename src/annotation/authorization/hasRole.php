<?php

namespace Luoyue\WebmanMvcCore\annotation\authorization;

#[\Attribute(\Attribute::TARGET_METHOD)]
class hasRole
{
    /**
     * 检查用户是否有某角色
     * @param string|array $role 角色标识
     */
    public function __construct(string|array $role)
    {
        if (empty($role)) {
            throw new \InvalidArgumentException('permissions is empty');
        }
    }
}