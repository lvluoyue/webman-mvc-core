<?php

namespace Luoyue\WebmanMvcCore\handler\bean;

use Luoyue\WebmanMvcCore\interface\UserInterface;

class AbstractUser implements UserInterface
{

    public function __construct(private int $id,
                                private string $username,
                                private ?string $password = null,
                                private ?string $token = null,
                                private array $role = [])
    {
    }

    /**
     * 获取用户id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 获取用户名
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * 获取密码
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * 获取认证key
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    /**
     * 设置密码
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * 设置token
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function addRole(string $role): void
    {
        $this->role[] = $role;
    }

    /**
     * 是否登录
     * @return bool
     */
    public function isLogin(): bool
    {
        return !$this->password && $this->token;
    }

}