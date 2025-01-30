<?php

namespace Luoyue\WebmanMvcCore\annotation\core\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use ReflectionClass;
use support\Container;

class ServiceParser implements IAnnotationParser
{
    public static function process(array $item): void
    {
        $interfaceNames = class_implements($item['class']);
        if ($name = $item['parameters']['name']) {
            self::addContainer($name, $item['class']);
        } else {
            array_map(fn (string $interface) => self::addContainer($interface, $item['class']), $interfaceNames);
        }
    }

    private static function addContainer($key, $value): void
    {
        $container = Container::instance();
        if ($container instanceof \Webman\Container) {
            $container->addDefinitions([$key => $container->get($value)]);
        } elseif ($container instanceof \DI\Container) {
            $container->set($key, \DI\get($value));
        }
    }
}
