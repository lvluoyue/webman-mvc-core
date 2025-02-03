<?php

namespace Luoyue\WebmanMvcCore\annotation\cache\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use Luoyue\aop\Aspect;
use Luoyue\aop\Collects\node\AspectNode;
use Luoyue\aop\enum\AdviceTypeEnum;
use Luoyue\WebmanMvcCore\aop\CacheAspect;

class CacheInvalidateParser implements iAnnotationParser
{

    public static array $cachedParams = [];

    public static function process(array $item): void
    {
        self::$cachedParams[$item['class'] . '::' . $item['method']] = $item['parameters'];
        $aspectCollects = Aspect::getInstance()->getAspectCollects();
        $proxyCollects = Aspect::getInstance()->getProxyCollects();
        $cachedReturning = $aspectCollects->getAspectNode($item['class'], 'cacheInvalidate') ?? new AspectNode(
            CacheAspect::class,
            'cacheInvalidate',
            AdviceTypeEnum::AfterReturning,
            []);
        $proxyCollects->getPointcutNode($item['class'])
            ->addPointcutMethod($item['method'], $cachedReturning);
        Aspect::getInstance()->scan();
    }

    public static function getParams(?string $sign = null): array
    {
        if (isset(self::$cachedParams[$sign])) {
            return self::$cachedParams[$sign];
        }
        return self::$cachedParams;
    }
}