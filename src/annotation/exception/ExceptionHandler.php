<?php

namespace Luoyue\WebmanMvcCore\annotation\exception;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\exception\parser\ExceptionHandlerParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ExceptionHandler extends AbstractAnnotationAttribute
{

    public function __construct(string $exceptionClass, ?string $app = null, bool $reportLog = true)
    {
        $this->setArguments(func_get_args());
    }

    public static function getParser(): string|array
    {
        return ExceptionHandlerParser::class;
    }

}