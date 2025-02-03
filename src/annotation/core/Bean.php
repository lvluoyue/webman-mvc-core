<?php

namespace Luoyue\WebmanMvcCore\annotation\core;

use LinFly\Annotation\AbstractAnnotationAttribute;
use Luoyue\WebmanMvcCore\annotation\core\parser\BeanParser;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Bean extends AbstractAnnotationAttribute
{

    /**
     * 把方法返回值注入到容器中（factory模式）
     * @param string $name
     * @param string|null $requireClass 类名，如果类名不存在则不注入
     */
    public function __construct(string $name = '', ?string $requireClass = null)
    {
        $this->setArguments(\func_get_args());
    }

    public static function getParser(): string|array
    {
        return BeanParser::class;
    }
}
