<?php

namespace Luoyue\WebmanMvcCore\annotation\core\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use support\Container;
use Webman\Event\Event;

class EventParser implements IAnnotationParser
{
    public static function process(array $item): void
    {
        Event::on($item['parameters']['name'], self::getCallable($item));
    }

    private static function getCallable($item): array
    {
        return [Container::get($item['class']), $item['method']];
    }

}
