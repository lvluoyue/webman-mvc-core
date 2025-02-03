<?php

namespace Luoyue\WebmanMvcCore\handler\bean;

use Luoyue\WebmanMvcCore\handler\PermissionHandler;
use Luoyue\WebmanMvcCore\interface\UserDetailsService;
use Luoyue\WebmanMvcCore\interface\UserInterface;
use support\Container;

class SessionUserDetailsService implements UserDetailsService
{

    public function getUser(): ?UserInterface
    {
        return request()->session()->get('user');
    }

    public function loadUserByUsername(string $user): ?UserInterface
    {
        /** @var PermissionHandler $service */
        $service = Container::get(PermissionHandler::class);
        $user = $service->getUser($user);
        return $user;
    }

    public function passwordEncoder(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function passwordVerify(string $password1, string $password2): bool
    {
        return password_verify($password1, $password2);
    }

    public function login(string $username, string $password): ?UserInterface
    {
        $user = $this->loadUserByUsername($username);
        if(!$user || !$this->passwordVerify($password, $user->getPassword())) {
            return null;
        }
        request()->session()->set('user', $user);
        return $user;
    }

    public function logout(): bool
    {
        request()->session()->flush();
        return true;
    }

}