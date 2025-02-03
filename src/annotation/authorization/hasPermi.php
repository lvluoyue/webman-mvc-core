<?php

namespace Luoyue\WebmanMvcCore\annotation\authorization;

#[\Attribute(\Attribute::TARGET_METHOD)]
class hasPermi
{

    /**
     * 检查用户是否有某权限
     * @param string|array $permissions 权限标识
     */
    public function __construct(string|array $permissions)
    {
        if (empty($permissions)) {
            throw new \InvalidArgumentException('permissions is empty');
        }
    }

}