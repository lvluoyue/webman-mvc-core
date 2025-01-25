<?php

namespace Luoyue\WebmanMvcCore\annotation\cache;

use LinFly\Annotation\AbstractAnnotationAttribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class cached extends AbstractAnnotationAttribute
{

    public function __construct(string $key = '', string $expire = '', string $driver = '')
    {
    }

}