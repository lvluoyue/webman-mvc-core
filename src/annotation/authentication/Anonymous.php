<?php

namespace Luoyue\WebmanMvcCore\annotation\authentication;

/**
 * 允许匿名访问（未登录）
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Anonymous
{

}