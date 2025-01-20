<?php

namespace luoyue\WebmanMvcAnnotationLibrary\annotation;

use LinFly\Annotation\AbstractAnnotationAttribute;
use luoyue\WebmanMvcAnnotationLibrary\annotation\parser\EventParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Event extends AbstractAnnotationAttribute
{
    public function __construct(string $name)
    {
        $this->setArguments(\func_get_args());
    }

    public static function getParser(): string|array
    {
        return EventParser::class;
    }
}
