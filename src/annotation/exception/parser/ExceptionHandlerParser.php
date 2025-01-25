<?php

namespace Luoyue\WebmanMvcCore\annotation\exception\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use ReflectionClass;
use Throwable;

class ExceptionHandlerParser implements IAnnotationParser
{

    private static array $exceptions = [];

    public static function process(array $item): void
    {
        $reflectionClass = new ReflectionClass($item['parameters']['exceptionClass']);
        if (!$reflectionClass->isSubclassOf(Throwable::class)) {
            throw new \InvalidArgumentException('Exception class must be a subclass of Throwable');
        }
        $reflectionMethod = new \ReflectionMethod($item['class'], $item['method']);
        $returnName = $reflectionMethod->getReturnType()?->getName();
        if($returnName !== \support\Response::class && $returnName !== \Webman\Http\Response::class) {
            throw new \InvalidArgumentException('Exception handler must return Response');
        }
        self::$exceptions[$item['parameters']['exceptionClass']] = [
            $item['class'],
            $item['method'],
            $item['parameters']['reportLog'],
        ];
    }

    public static function getException(?string $exceptionClass = null): array
    {
        if ($exceptionClass) {
            return self::$exceptions[$exceptionClass] ?? [];
        }
        return self::$exceptions;
    }
}