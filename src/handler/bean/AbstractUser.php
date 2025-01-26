<?php

namespace Luoyue\WebmanMvcCore\handler\bean;

use Luoyue\WebmanMvcCore\interface\UserInterface;

class AbstractUser implements UserInterface
{

    public function __construct(private int $id,
                                private string $username,
                                private ?string $password = null,
                                private ?string $authKey = null)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAuthKey(): ?string
    {
        return $this->authKey;
    }

    public function isLogin(): bool
    {
        return !$this->password && $this->authKey;
    }
}