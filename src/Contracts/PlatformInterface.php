<?php
/**
 * User: szliugx@gmail.com
 * Date: 17/8/5
 * Time: 上午11:35
 */

namespace WenXi\TtsApi\Contracts;

use WenXi\TtsApi\Support\Config;

interface PlatformInterface
{
    public function send($text, Config $config);
}

