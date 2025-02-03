<?php

namespace Luoyue\WebmanMvcCore\annotation\core;

/**
 * 排序（未实现）
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Order
{

    public function __construct(public int $order)
    {
    }

}