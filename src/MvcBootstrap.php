<?php

namespace Luoyue\WebmanMvcCore;

use Luoyue\WebmanMvcCore\handler\AuthenticationHandler;
use Luoyue\WebmanMvcCore\interface\UserDetailsService;
use support\Container;
use Webman\Bootstrap;
use Workerman\Worker;

class MvcBootstrap implements Bootstrap
{

    public static function start(?Worker $worker)
    {
        $container = Container::instance();
        if ($container instanceof \Webman\Container) {
            Container::make(UserDetailsService::class, Container::get(AuthenticationHandler::class));
        } else if ($container instanceof \DI\Container) {
            $container->set(UserDetailsService::class, \DI\autowire(AuthenticationHandler::class));
        }
    }
}