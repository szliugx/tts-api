<?php
/**
 * User: szliugx@gmail.com
 * Date: 17/8/5
 * Time: 下午11:10
 */

namespace WenXi\TtsApi\Exceptions;

class PlatformErrorException extends Exception
{
    /**
     * @var array
     */
    public $raw = [];

    /**
     * 平台异常异常处理
     * @param string $message
     * @param int $code
     * @param array $raw
     */
    public function __construct($message, $code, array $raw = [])
    {
        parent::__construct($message, intval($code));

        $this->raw = $raw;
    }
}