<?php
/**
 * User: szliugx@gmail.com
 * Date: 17/8/5
 * Time: 上午11:29
 */

namespace VinCi\TtsApi;

use VinCi\TtsApi\Contracts\PlatformInterface;
use VinCi\TtsApi\Exceptions\InvalidArgumentException;
use VinCi\TtsApi\Support\Config;

/**
 * 语音合成API
 * Class Tts
 * @package VinCi\TtsApi
 */
class Tts
{
    protected $config;

    protected $platform;

    function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    public function send($text, $platformName)
    {
        return $this->getPlatform($platformName)->send($text, $this->config);
    }

    /**
     * @param $name
     * @return PlatformInterface
     * @throws InvalidArgumentException
     */
    protected function getPlatform($name)
    {
        return $this->createPlatform($name);
    }

    /**
     * 根据平台名称创建平台
     * @param $name
     * @return PlatformInterface
     * @throws InvalidArgumentException
     */
    protected function createPlatform($name)
    {
        $className = $this->formatPlatformClassName($name);
        $platform = $this->makePlatform($className);


        if (!($platform instanceof PlatformInterface)) {
            throw new InvalidArgumentException(sprintf('Platform "%s" not inherited from %s.', $name));
        }

        return $platform;
    }

    /**
     * 拼接平台实现类路径
     * @param $name
     * @return string
     */
    protected function formatPlatformClassName($name)
    {
        $name = ucfirst(str_replace(['-', '_', ''], '', $name));

        return __NAMESPACE__."\\Platforms\\{$name}Platform";
    }

    /**
     * 创建平台实例
     * @param $platform
     * @return \VinCi\TtsApi\Contracts\PlatformInterface
     * @throws InvalidArgumentException
     */
    protected function makePlatform($platform)
    {
        if (!class_exists($platform)) {

            throw new InvalidArgumentException(sprintf('Platform "%s" not exists.', $platform));
        }

        return $this->platform = new $platform();
    }

}
