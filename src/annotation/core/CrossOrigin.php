<?php

namespace Luoyue\WebmanMvcCore\annotation\core;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_METHOD)]
class CrossOrigin
{

    public function __construct(string $origin = '*', string $methods = 'GET,POST,PUT,PATCH,DELETE,OPTIONS', string $headers = '*')
    {
    }

}