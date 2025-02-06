<?php

namespace Luoyue\WebmanMvcCore\aop;

use Luoyue\aop\Attributes\AfterReturning;
use Luoyue\aop\Attributes\AfterThrowing;
use Luoyue\aop\Attributes\Before;
use Luoyue\aop\interfaces\ProceedingJoinPointInterface;
use Luoyue\WebmanMvcCore\annotation\orm\parser\TransactionalParser;
use support\Context;

class TransactionalAspect
{

//    #[Before('')]
//    public function transactionBefore(ProceedingJoinPointInterface $proceedingJoinPoint): mixed
//    {
//        return Db::transaction(fn() => $proceedingJoinPoint->process());
//    }

    #[Before('')]
    public function transactionBefore(ProceedingJoinPointInterface $proceedingJoinPoint): mixed
    {
        if(!Context::has(TransactionalContext::class)) {
            $sign = $proceedingJoinPoint->getClassName() . '::' . $proceedingJoinPoint->getMethodName();
            $props = call_user_func([TransactionalParser::class, 'getParams'], $sign);
            Context::set(TransactionalContext::class, new TransactionalContext(...$props));
        }
        Context::get(TransactionalContext::class)->beginTransaction();
        return $proceedingJoinPoint->process();
    }

    #[AfterReturning('')]
    public function transactionAfterReturning($res, ProceedingJoinPointInterface $proceedingJoinPoint): mixed
    {
        Context::get(TransactionalContext::class)->commit();
        return null;
    }

    #[AfterThrowing('')]
    public function transactionAfterThrowing($throwable, ProceedingJoinPointInterface $proceedingJoinPoint): mixed
    {
        Context::get(TransactionalContext::class)->rollback($throwable);
        return null;
    }

}