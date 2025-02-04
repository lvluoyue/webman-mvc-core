<?php

namespace Luoyue\WebmanMvcCore\annotation\orm\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use Luoyue\aop\Aspect;
use Luoyue\aop\Collects\node\AspectNode;
use Luoyue\aop\enum\AdviceTypeEnum;
use Luoyue\WebmanMvcCore\annotation\cache\parser\CachePutParser;
use Luoyue\WebmanMvcCore\aop\TransactionalAspect;

class TransactionalParser implements IAnnotationParser
{

    private static array $transactional;

    public static function process(array $item): void
    {
        self::$transactional[$item['class'] . '::' . $item['method']] = $item['parameters'];
        $aspectCollects = Aspect::getInstance()->getAspectCollects();
        $proxyCollects = Aspect::getInstance()->getProxyCollects();
        $cachedBefore = $aspectCollects->getAspectNode(TransactionalAspect::class, 'transactionBefore') ?? new AspectNode(
            TransactionalAspect::class,
            'transactionBefore',
            AdviceTypeEnum::Before,
            []);
        $proxyCollects->getPointcutNode($item['class'])
            ->addPointcutMethod($item['method'], $cachedBefore);
        Aspect::getInstance()->scan();
    }
}