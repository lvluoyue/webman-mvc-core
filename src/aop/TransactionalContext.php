<?php

namespace Luoyue\WebmanMvcCore\aop;

use support\Db;

class TransactionalContext
{

    private int $index = 0;

    public function __construct()
    {

    }

    public function beginTransaction()
    {
        $this->index++;
        Db::beginTransaction();
    }

    public function rollback()
    {
        Db::rollBack();
    }

    public function __destruct()
    {
        for ($i = 0; $i < $this->index; $i++) {
            Db::rollBack();
        }
    }

}