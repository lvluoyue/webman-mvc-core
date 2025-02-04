<?php

namespace Luoyue\WebmanMvcCore\aop;

use Luoyue\aop\Attributes\Before;
use Luoyue\aop\interfaces\ProceedingJoinPointInterface;
use support\Db;

class TransactionalAspect
{

    #[Before('')]
    public function transactionBefore(ProceedingJoinPointInterface $proceedingJoinPoint): mixed
    {
        return Db::transaction(fn() => $proceedingJoinPoint->process());
    }

}