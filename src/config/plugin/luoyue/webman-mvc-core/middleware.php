<?php

$middleware = [];

if (config('plugin.luoyue.webman-mvc-core.app.permission.enable')) {
    $middleware[] = \Luoyue\WebmanMvcCore\middleware\PermissionMiddleware::class;
}

return ['@' => $middleware];