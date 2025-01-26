<?php

namespace Luoyue\WebmanMvcCore\annotation\core;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Order
{

    public function __construct(public int $order)
    {
    }

}