<?php

namespace Luoyue\WebmanMvcCore\annotation\exception;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\exception\parser\ExceptionHandlerParser;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ExceptionHandler extends AbstractAnnotationAttribute
{

    public function __construct(string|array $exceptionClass, string|array|null $app = null, public ?string $logChannel = 'default')
    {
        $this->setArguments(func_get_args());
    }

    public static function getParser(): string|array
    {
        return ExceptionHandlerParser::class;
    }

}