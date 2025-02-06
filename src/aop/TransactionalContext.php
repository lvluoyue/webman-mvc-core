<?php

namespace Luoyue\WebmanMvcCore\aop;

use support\Db;

class TransactionalContext
{

    private int $index = 0;

    public function __construct(private array $rollbackFor)
    {

    }

    public function beginTransaction()
    {
        Db::beginTransaction();
        $this->index++;
    }

    public function rollback(\Throwable $throwable)
    {
        if ($this->index <= 0) {
            return;
        }
        foreach ($this->rollbackFor as $rollbackFor) {
            if ($throwable instanceof $rollbackFor) {
                Db::rollBack();
                $this->index--;
                return;
            }
        }
    }

    public function commit()
    {
        if ($this->index <= 0) {
            return;
        }
        Db::commit();
        $this->index--;
    }

    public function __destruct()
    {
        for ($i = 0; $i < $this->index; $i++) {
            $this->commit();
        }
    }

}