<?php

namespace Luoyue\WebmanMvcCore\annotation\core;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\core\parser\BeanParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Bean extends AbstractAnnotationAttribute
{
    public function __construct(string $name = '', string $requireClass = '')
    {
        $this->setArguments(\func_get_args());
    }

    public static function getParser(): string|array
    {
        return BeanParser::class;
    }
}
