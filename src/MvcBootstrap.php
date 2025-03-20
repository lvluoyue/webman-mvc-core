<?php

namespace Luoyue\WebmanMvcCore;

use Illuminate\Database\Events\QueryExecuted;
use support\Db;
use Webman\Bootstrap;
use Workerman\Worker;
use support\Logger;

class MvcBootstrap implements Bootstrap
{

    public static function start(?Worker $worker)
    {
        if (function_exists('env') && env('SERVER_OPEN_BROWSER', false)) {
            $appName = env('SERVER_APP_NAME', 'webman');
            $lockFile = runtime_path("windows/start_$appName.php");
            if (\DIRECTORY_SEPARATOR !== '/' && time() - filemtime($lockFile) <= 3 && $worker->name === $appName) {
                exec('start http://127.0.0.1:' . env('SERVER_APP_PROT', 8787));
            }
        }
        if (class_exists(QueryExecuted::class)) {
            Db::connection()->listen(function (QueryExecuted $queryExecuted) {
                if (isset($queryExecuted->sql) and $queryExecuted->sql !== "select 1") {
                    $sql = str_replace("?", "%s", trim($queryExecuted->sql));
                    foreach ($queryExecuted->bindings as $i => $binding) {
                        if ($binding instanceof \DateTime) {
                            $queryExecuted->bindings[$i] = $binding->format("'Y-m-d H:i:s'");
                        } else {
                            if (is_string($binding)) {
                                $queryExecuted->bindings[$i] = "'$binding'";
                            }
                        }
                    }
                    try {
                        $sql = vsprintf($sql, $queryExecuted->bindings);
                    } finally {
                        $log = sprintf("[SQL] %s [ RunTime:%.7f ms ]", $sql, $queryExecuted->time / 1000);
                        if (class_exists(Logger::class)) {
                            Logger::sql($log);
                        } else {
                            echo $log . PHP_EOL;
                        }
                    }
                }
            });
        }
    }
}