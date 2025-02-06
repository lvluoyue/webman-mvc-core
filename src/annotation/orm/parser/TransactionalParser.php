<?php

namespace Luoyue\WebmanMvcCore\annotation\orm\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use Luoyue\aop\Aspect;
use Luoyue\aop\Collects\node\AspectNode;
use Luoyue\aop\enum\AdviceTypeEnum;
use Luoyue\WebmanMvcCore\aop\TransactionalAspect;

class TransactionalParser implements IAnnotationParser
{

    private static array $transactional;

    public static function process(array $item): void
    {
        $item['parameters']['rollbackFor'] = (array)$item['parameters']['rollbackFor'];
        self::$transactional[$item['class'] . '::' . $item['method']] = $item['parameters'];
        $aspectCollects = Aspect::getInstance()->getAspectCollects();
        $proxyCollects = Aspect::getInstance()->getProxyCollects();
        $transactionBefore = $aspectCollects->getAspectNode(TransactionalAspect::class, 'transactionBefore') ?? new AspectNode(
            TransactionalAspect::class,
            'transactionBefore',
            AdviceTypeEnum::Before,
            []);
        $transactionAfterReturning = $aspectCollects->getAspectNode(TransactionalAspect::class, 'transactionAfterReturning') ?? new AspectNode(
            TransactionalAspect::class,
            'transactionAfterReturning',
            AdviceTypeEnum::AfterReturning,
            []);
        $transactionAfterThrowing = $aspectCollects->getAspectNode(TransactionalAspect::class, 'transactionAfterThrowing') ?? new AspectNode(
            TransactionalAspect::class,
            'transactionAfterThrowing',
            AdviceTypeEnum::AfterThrowing,
            []);
        $proxyCollects->getPointcutNode($item['class'])
            ->addPointcutMethod($item['method'], $transactionBefore)
            ->addPointcutMethod($item['method'], $transactionAfterReturning)
            ->addPointcutMethod($item['method'], $transactionAfterThrowing);
        Aspect::getInstance()->scan();
    }

    public static function getParams(?string $sign = null): array
    {
        if (isset(self::$transactional[$sign])) {
            return self::$transactional[$sign];
        }
        return self::$transactional;
    }
}