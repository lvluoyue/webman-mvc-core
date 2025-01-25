<?php

namespace Luoyue\WebmanMvcCore\middleware;

use support\Context;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class JwtAuthenticationMiddleware implements MiddlewareInterface
{

    public function process(Request $request, callable $handler): Response
    {
        Context::set('user', 'eve');
        return $handler($request);
    }
}