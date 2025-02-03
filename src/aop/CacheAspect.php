<?php

namespace Luoyue\WebmanMvcCore\aop;

use Luoyue\aop\Attributes\AfterReturning;
use Luoyue\aop\Attributes\Before;
use Luoyue\aop\interfaces\ProceedingJoinPointInterface;
use Luoyue\WebmanMvcCore\annotation\cache\parser\CachedParser;
use support\Cache;

class CacheAspect
{
    #[AfterReturning('')]
    public function cachedReturning($res, ProceedingJoinPointInterface $proceedingJoinPoint): mixed
    {
        $data = $this->getCacheData($proceedingJoinPoint);
        Cache::store($data['driver'])->set($data['key'], $res, $data['expire']);
        return null;
    }

    #[Before('')]
    public function cachedBefore(ProceedingJoinPointInterface $proceedingJoinPoint): mixed
    {
        $data = $this->getCacheData($proceedingJoinPoint);
        $cache = Cache::store($data['driver']);
        if($cache->has($data['key'])) {
            return $cache->get($data['key']);
        }
        return null;
    }

    private function getCacheData(ProceedingJoinPointInterface $proceedingJoinPoint): array
    {
        $reflectionMethod = $proceedingJoinPoint->getClassName() . '::' . $proceedingJoinPoint->getMethodName();
        [$name, $key, $expire, $driver] = CachedParser::getParams($reflectionMethod);
        return [
            'key' => $name . '_' . $this->getParserKey($key, $proceedingJoinPoint->getArguments()),//缓存键
            'expire' => $expire,// 过期时间
            'driver' => $driver,// 缓存驱动
        ];
    }

    /**
     * 解析缓存键
     * @param string $key
     * @param array $args
     * @return string
     */
    private function getParserKey(string $key, array $args): string
    {
        return preg_replace_callback('/\{\$(.*?)\}/', function ($matches) use ($args) {$keys = explode('[', $matches[1]);
            $keys = array_map(fn ($key) => trim($key, ']'), $keys);
            $value = $args;
            foreach ($keys as $key) {
                if (is_array($value) && array_key_exists($key, $value)) {
                    $value = $value[$key];
                } else {
                    return ''; // 如果键不存在，返回空字符串
                }
            }
            // 如果值是数组，则使用crc32进行哈希
            return is_array($value) ? crc32(json_encode($value, JSON_UNESCAPED_UNICODE)) : $value;
        }, $key);
    }
}