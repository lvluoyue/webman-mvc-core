<?php

namespace Luoyue\WebmanMvcCore\interface;

interface UserDetailsService
{
    /**
     * 获取当前用户
     * @return UserInterface|null 用户对象，为空则未找到
     */
    public function getUser(): ?UserInterface;

    /**
     * 查找用户名
     * @param string $user 用户名
     * @return UserInterface|null 用户对象，为空则未找到
     */
    public function loadUserByUsername(string $user): ?UserInterface;

    /**
     * 密码加密器
     * @param string $password 密码
     * @return string 加密后的密码
     */
    public function passwordEncoder(string $password): string;
}