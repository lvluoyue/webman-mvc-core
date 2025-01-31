<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

use LinFly\Annotation\AbstractAnnotationAttribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Cached extends AbstractAnnotationAttribute
{

    public function __construct(string $name = '', string $key = '', string $expire = '', string $driver = '')
    {
    }

}