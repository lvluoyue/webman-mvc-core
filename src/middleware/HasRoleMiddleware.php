<?php

namespace Luoyue\WebmanMvcCore\middleware;

use Casbin\WebmanPermission\Permission;
use Luoyue\WebmanMvcCore\annotation\authentication\Anonymous;
use Luoyue\WebmanMvcCore\annotation\authorization\hasRole;
use Luoyue\WebmanMvcCore\exception\UserException;
use Luoyue\WebmanMvcCore\interface\UserDetailsService;
use support\Container;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class HasRoleMiddleware implements MiddlewareInterface
{

    public function process(Request $request, callable $handler): Response
    {
        $reflectionMethod = new \ReflectionMethod($request->controller, $request->action);
        if($reflectionMethod->getAttributes(Anonymous::class)) {
            return $handler($request);
        }
        /** @var UserDetailsService $service */
        $service = Container::get(UserDetailsService::class);
        $userId = $service->getUser()?->getId();
        if (!$userId) {
            throw new UserException('not User');
        }
        $hasRoleAttributes = $reflectionMethod->getAttributes(hasRole::class);
        if($hasRoleAttributes) {
            foreach ($hasRoleAttributes as $hasRoleAttribute) {
                $role = $hasRoleAttribute->getArguments()[0];
                if(!Permission::hasRoleForUser((string)$userId, $role)) {
                    return response('您没有权限访问', 403);
                }
            }
        }
        return $handler($request);
    }
}