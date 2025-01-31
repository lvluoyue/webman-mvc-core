<?php

namespace Luoyue\WebmanMvcCore;

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
    }
}