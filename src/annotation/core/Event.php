<?php

namespace Luoyue\WebmanMvcCore\annotation\core;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\core\parser\EventParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Event extends AbstractAnnotationAttribute
{
    /**
     * 事件注解，依赖webman/event组件
     * @param string $name 事件名称
     */
    public function __construct(string $name)
    {
        $this->setArguments(\func_get_args());
    }

    public static function getParser(): string|array
    {
        return EventParser::class;
    }
}
