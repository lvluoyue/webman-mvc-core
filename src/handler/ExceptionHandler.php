<?php

namespace Luoyue\WebmanMvcCore\handler;

use Luoyue\WebmanMvcCore\annotation\exception\parser\ExceptionHandlerParser;
use Psr\Log\LoggerInterface;
use support\Container;
use support\Log;
use Throwable;
use Webman\Exception\ExceptionHandlerInterface;
use Webman\Http\Request;
use Webman\Http\Response;

class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger = null;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * ExceptionHandler constructor.
     * @param $logger
     * @param $debug
     */
    public function __construct($logger, $debug)
    {
        $this->logger = $logger;
        $this->debug = $debug;
    }

    /**
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        foreach (ExceptionHandlerParser::getExceptions() as $exceptionClass => $exceptionHandler) {
            [$handlerClass, $handlerMethod, $app, $logChannel] = $exceptionHandler;
            if ($exception instanceof $exceptionClass && !$logChannel) {
                return;
            }
        }
        $logs = '';
        if ($request = \request()) {
            $logs = $request->getRealIp() . ' ' . $request->method() . ' ' . trim($request->fullUrl(), '/');
        }
        $this->logger->error($logs . PHP_EOL . $exception);
        if($logChannel) {
            Log::channel($logChannel)->error($logs . PHP_EOL . $exception);
        }
    }

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     */
    public function render(Request $request, Throwable $exception): Response
    {
        foreach (ExceptionHandlerParser::getExceptions() as $exceptionClass => $exceptionHandler) {
            if ($exception instanceof $exceptionClass) {
                [$handlerClass, $handlerMethod, $apps] = $exceptionHandler;
                if (empty($apps)) {
                    return Container::get($handlerClass)->{$handlerMethod}($request, $exception);
                }
                foreach ((array) $apps as $app) {
                    if($request->app == $app) {
                        return Container::get($handlerClass)->{$handlerMethod}($request, $exception);
                    }
                }
            }
        }
        if (method_exists($exception, 'render') && ($response = $exception->render($request))) {
            return $response;
        }
        $code = $exception->getCode();
        if ($request->expectsJson()) {
            $json = ['code' => $code ?: 500, 'msg' => $this->debug ? $exception->getMessage() : 'Server internal error'];
            $this->debug && $json['traces'] = (string)$exception;
            return new Response(200, ['Content-Type' => 'application/json'],
                json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
        $error = $this->debug ? nl2br((string)$exception) : 'Server internal error';
        return new Response(500, [], $error);
    }

    /**
     * Compatible $this->_debug
     *
     * @param string $name
     * @return bool|null
     */
    public function __get(string $name)
    {
        if ($name === '_debug') {
            return $this->debug;
        }
        return null;
    }
}
