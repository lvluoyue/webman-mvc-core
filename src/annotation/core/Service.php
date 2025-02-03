<?php

namespace Luoyue\WebmanMvcCore\annotation\core;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\core\parser\ServiceParser;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Service extends AbstractAnnotationAttribute
{
    /**
     * Service注解
     * 据类的interface注入到容器中.
     * @param string $name 服务名称
     */
    public function __construct(string $name = '')
    {
        $this->setArguments(\func_get_args());
    }

    public static function getParser(): string|array
    {
        return ServiceParser::class;
    }
}
