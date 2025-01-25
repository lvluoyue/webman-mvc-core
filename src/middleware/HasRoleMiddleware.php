<?php

namespace Luoyue\WebmanMvcCore\middleware;

use Casbin\WebmanPermission\Permission;
use Luoyue\WebmanMvcCore\annotation\authentication\Anonymous;
use Luoyue\WebmanMvcCore\annotation\authorization\hasRole;
use Luoyue\WebmanMvcCore\exception\UserException;
use Webman\Context;
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
        $user = Context::get('user');
        if(!$user || !is_string($user)) {
            throw new UserException('user is not User');
        }
        $hasRoleAttributes = $reflectionMethod->getAttributes(hasRole::class);
        if($hasRoleAttributes) {
            foreach ($hasRoleAttributes as $hasRoleAttribute) {
                /** @var hasRole $hasRole */
                $hasRole = $hasRoleAttribute->newInstance();
                if(!Permission::hasRoleForUser($user, $hasRole->role)) {
                    return response('您没有权限访问', 403);
                }
            }
        }
        return $handler($request);
    }
}