<?php

namespace Luoyue\WebmanMvcCore\interface;

interface UserInterface
{

    /**
     * 获取用户id
     * @return int
     */
    public function getId(): int;

    /**
     * 获取用户名
     * @return string
     */
    public function getUsername(): string;

    /**
     * 获取密码
     * @return string
     */
    public function getPassword(): ?string;

    /**
     * 获取认证key
     * @return string|null
     */
    public function getAuthKey(): ?string;

    /**
     * 是否登录
     * @return bool
     */
    public function isLogin(): bool;


}