<?php

namespace Luoyue\WebmanMvcCore\handler;

use Luoyue\WebmanMvcCore\handler\bean\AbstractUser;
use Luoyue\WebmanMvcCore\interface\UserDetailsService;
use Luoyue\WebmanMvcCore\interface\UserInterface;

class AuthenticationHandler implements UserDetailsService
{

    public function getUser(): ?UserInterface
    {
        return new AbstractUser(1, 'eve');
    }

    public function loadUserByUsername(string $user): ?UserInterface
    {
        return new AbstractUser(1, $user);
    }

    public function passwordEncoder(string $password): string
    {
        return $password;
    }

}