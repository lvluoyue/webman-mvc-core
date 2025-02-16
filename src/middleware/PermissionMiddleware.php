<?php

namespace Luoyue\WebmanMvcCore\middleware;

use Casbin\WebmanPermission\Permission;
use Luoyue\WebmanMvcCore\annotation\authentication\Anonymous;
use Luoyue\WebmanMvcCore\annotation\authorization\hasPermi;
use Luoyue\WebmanMvcCore\annotation\authorization\hasRole;
use Luoyue\WebmanMvcCore\exception\PermissionException;
use ReflectionAttribute;
use ReflectionMethod;
use Webman\Http\Request;
use Webman\Http\Response;
use WebmanTech\Auth\Interfaces\IdentityInterface;

class PermissionMiddleware extends \WebmanTech\Auth\Middleware\Authentication
{

    protected array $config;

    public function __construct()
    {
        $this->config = config('plugin.luoyue.webman-mvc-core.app.permission');
    }

    public function process(Request $request, callable $handler): Response
    {
        if (!$this->config['enable'] ?? true) {
            return $handler($request);
        }
        $guard = $this->getGuard();
        $identity = $guard->getAuthenticationMethod()->authenticate($request);
        if ($identity instanceof IdentityInterface) {
            $guard->login($identity);
        }

        if ($this->filterAnnotation($this->getAttributes(), Anonymous::class)) {
            return $handler($request);
        }

        if ($this->isOptionalRoute($request)) {
            return $handler($request);
        }

        if ($userId = $guard->getId()) {
            if (!$this->hasPermission($userId)) {
                throw new PermissionException('permission denied');
            }
            return $handler($request);
        }
        return $guard->getAuthenticationFailedHandler()->handle($request);
    }

    private function hasPermission(string $userId): bool
    {
        $attributes = $this->getAttributes();
        $hasPermiAttributes = $this->filterAnnotation($attributes, hasPermi::class);
        //遍历注解
        foreach ($hasPermiAttributes as $hasPermiAttribute) {
            //遍历权限
            foreach ((array)$hasPermiAttribute->getArguments()[0] as $prmi) {
                $prmi = explode(':', $prmi);
                //权限或
                if (Permission::hasPermissionForUser($userId, ...$prmi)) {
                    return true;
                }
            }
        }

        $hasRoleAttributes = $this->filterAnnotation($attributes, hasRole::class);
        foreach ($hasRoleAttributes as $hasRoleAttribute) {
            foreach ((array)$hasRoleAttribute->getArguments()[0] as $role) {
                $role = explode(':', $role);
                if (Permission::hasRoleForUser($userId, ...$role)) {
                    return true;
                }
            }
        }
        return !$hasPermiAttributes && !$hasRoleAttributes;
    }

    /**
     * @return ReflectionAttribute[]
     * @throws \ReflectionException
     */
    private function getAttributes(): array
    {
        return (new ReflectionMethod(request()->controller, request()->action))->getAttributes();
    }


    /**
     * 过滤注解
     * @param ReflectionAttribute[] $attributes
     * @param string $annotationName
     * @return array
     */
    private function filterAnnotation(array $attributes, string $annotationName): array
    {
        return array_filter($attributes, function (ReflectionAttribute $attribute) use ($annotationName) {
            return $attribute->getName() === $annotationName;
        });
    }

}