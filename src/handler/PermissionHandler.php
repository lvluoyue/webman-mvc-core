<?php

namespace Luoyue\WebmanMvcCore\handler;

use Casbin\WebmanPermission\Permission;
use Luoyue\WebmanMvcCore\annotation\authentication\Anonymous;
use Luoyue\WebmanMvcCore\annotation\authorization\hasPermi;
use Luoyue\WebmanMvcCore\annotation\authorization\hasRole;
use Luoyue\WebmanMvcCore\exception\UserException;
use Luoyue\WebmanMvcCore\handler\bean\AbstractUser;
use Luoyue\WebmanMvcCore\handler\bean\SessionUserDetailsService;
use Luoyue\WebmanMvcCore\interface\UserDetailsService;
use ReflectionAttribute;
use support\Container;
use Webman\Http\Request;

class PermissionHandler
{

    /** @var AbstractUser[] $users */
    private array $users;

    private array $config;

    const default = [
        'username' => 'admin',
        'password' => '123456',
        'role' => ['index'],
    ];

    public function __construct()
    {
        $container = Container::instance();
        if ($container instanceof \Webman\Container) {
            Container::make(UserDetailsService::class, Container::get(SessionUserDetailsService::class));
        } else if ($container instanceof \DI\Container) {
            $container->set(UserDetailsService::class, \DI\autowire(SessionUserDetailsService::class));
        }
        $this->config = array_merge(self::default, config('plugin.luoyue.webman-mvc-core.app.permission'));
    }

    /**
     * @param ?string $username
     * @return array|AbstractUser|null
     */
    public function getUser(?string $username = null): array|AbstractUser|null
    {
        if (!isset($this->users)) {
            $this->users[$this->config['username']] =
                new AbstractUser(1, $this->config['username'],
                    password_hash($this->config['password'], PASSWORD_DEFAULT),
                    null,
                    $this->config['role']);
            foreach ($this->config['role'] as $role) {
                Permission::addRoleForUser('1', $role);
            }
        }
        return is_null($username) ? $this->users : ($this->users[$username] ?? null);
    }

    public function addUser(AbstractUser $user): void
    {
        $this->users[$user->getUsername()] = $user;
    }

    public function process(Request $request): bool
    {
        if (!$this->config['enable'] ?? true) {
            return true;
        }
        $reflectionMethod = new \ReflectionMethod($request->controller, $request->action);
        $attributes = $reflectionMethod->getAttributes();

        $anonymous = $this->filterAnnotation($attributes, Anonymous::class);
        if ($anonymous) {
            return true;
        }

        /** @var UserDetailsService $service */
        $service = Container::get(UserDetailsService::class);
        $userId = (string)$service->getUser()?->getId();
        if (!$userId) {
            throw new UserException('user is not User');
        }
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

        return false;
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