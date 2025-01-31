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
     * 从数据库查找用户名
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

    /**
     * 密码验证器
     * @param string $password1 用户输入密码
     * @param string $password2 数据库密码
     * @return bool
     */
    public function passwordVerify(string $password1, string $password2): bool;

    /**
     * 登录
     * @param string $user 用户名
     * @param string $password 密码
     * @return UserInterface|null 用户对象，为空则登录失败
     */
    public function login(string $username, string $password): ?UserInterface;
}