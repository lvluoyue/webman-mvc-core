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
        if(function_exists('env')) {
            /** @noinspection PhpUndefinedFunctionInspection */
            $appName = env('SERVER_APP_NAME', 'webman');
            $lockFile = runtime_path("windows/start_$appName.php");
            /** @noinspection PhpUndefinedFunctionInspection */
            if (env('SERVER_OPEN_BROWSER', false) && \DIRECTORY_SEPARATOR !== '/' && time() - filemtime($lockFile) <= 3) {
                /** @noinspection PhpUndefinedFunctionInspection */
                exec('start http://127.0.0.1:' . env('SERVER_APP_PROT', 8787));
            }
        }
        $container = Container::instance();
        if ($container instanceof \Webman\Container) {
            Container::make(UserDetailsService::class, Container::get(AuthenticationHandler::class));
        } else if ($container instanceof \DI\Container) {
            $container->set(UserDetailsService::class, \DI\autowire(AuthenticationHandler::class));
        }
    }
}