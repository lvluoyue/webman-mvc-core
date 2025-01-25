<?php

return [
    "@" => [
        \Luoyue\WebmanMvcCore\middleware\JwtAuthenticationMiddleware::class,
        \Luoyue\WebmanMvcCore\middleware\HasRoleMiddleware::class,
        \Luoyue\WebmanMvcCore\middleware\HasPermiMiddleware::class
    ]
];