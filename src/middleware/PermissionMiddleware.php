<?php

namespace Luoyue\WebmanMvcCore\middleware;

use Luoyue\WebmanMvcCore\exception\PermissionException;
use Luoyue\WebmanMvcCore\handler\PermissionHandler;
use support\Container;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class PermissionMiddleware implements MiddlewareInterface
{

    public function process(Request $request, callable $handler): Response
    {
        /** @var PermissionHandler $service */
        $service = Container::get(PermissionHandler::class);
        if(!$service->process($request)) {
            throw new PermissionException('您没有权限访问');
        }
        return $handler($request);
    }
}