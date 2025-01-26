<?php

namespace Luoyue\WebmanMvcCore\middleware;

use Casbin\WebmanPermission\Permission;
use Luoyue\WebmanMvcCore\annotation\authorization\hasPermi;
use Luoyue\WebmanMvcCore\exception\UserException;
use Luoyue\WebmanMvcCore\interface\UserDetailsService;
use support\Container;
use Webman\Context;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class HasPermiMiddleware implements MiddlewareInterface
{

    public function process(Request $request, callable $handler): Response
    {
        /** @var UserDetailsService $service */
        $service = Container::get(UserDetailsService::class);
        $userId = $service->getUser()?->getId();
        if (!$userId) {
            throw new UserException('user is not User');
        }
        $reflectionMethod = new \ReflectionMethod($request->controller, $request->action);
        $hasRoleAttributes = $reflectionMethod->getAttributes(hasPermi::class);
        if ($hasRoleAttributes) {
            foreach ($hasRoleAttributes as $hasRoleAttribute) {
                /** @var hasPermi $hasRole */
                $hasRole = $hasRoleAttribute->newInstance();
                if (!Permission::hasPermissionForUser($userId, ...$hasRole->permissions)) {
                    return response('您没有权限访问', 403);
                }
            }
        }
        return $handler($request);
    }
}