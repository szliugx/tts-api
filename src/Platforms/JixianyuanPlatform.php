<?php
/**
 * User: szliugx@gmail.com
 * Date: 17/8/5
 * Time: 上午11:45
 */

namespace WenXi\TtsApi\Platforms;

use WenXi\TtsApi\Contracts\PlatformInterface;
use WenXi\TtsApi\Exceptions\InvalidArgumentException;
use WenXi\TtsApi\Support\Config;
use WenXi\TtsApi\Traits\HasHttpRequest;

class JixianyuanPlatform implements PlatformInterface
{
    use HasHttpRequest;

    const REQUEST_URL = "http://speech.api.jixianyuan.com/tts";

    public function send($text, Config $config)
    {
        // apiKey	 String	 是	 Api key
        // signature	 String	 是	 签名字符串
        // expires	 Int	 是	 请求超时时间，自UTC1970-01-01开始的秒数
        // text	 String	 是	 需要合成的文本
        // voice	 String	 否	 声音类型，语言+性别，默认cnmale
        // format	  String	 否	 音频格式（WAV，MP3），默认MP3
        // speed	 Float	 否	 语音速度，0.5--5 默认1
        $params = [];
        $params['apiKey'] = $config->get('apiKey');
        $params['expires'] = $config->get('expires');
        $params['text'] = $text;
        $params['voice'] = $config->get('voice');
        $params['format'] = $config->get('format');
        $params['speed'] = $config->get('speed');

        $sendParams = $this->formatParams($params);
        $str = $this->joinParams($sendParams);
        $apiSecret = $config->get('apiSecret');
        $sendParams['signature'] = hash_hmac("sha1", $str, $apiSecret);

        $response = $this->post(self::REQUEST_URL,$sendParams);

        return $response;
    }

    /**
     * 删除空元素
     * @param array $params
     * @return array
     */
    protected function deleteNullOfElement(array $params)
    {

        return array_filter($params);
    }

    /**
     * 格式化数组
     * @param array $params
     * @return array $params
     * @throws InvalidArgumentException
     */
    protected function formatParams(array $params)
    {
        $params = $this->deleteNullOfElement($params);

        if(empty($params)){
            throw new InvalidArgumentException("极限元平台tts参数不允许未空");
        }
        ksort($params);

        return $params;
    }

    /**
     * 拼接字符串
     * @param array $params
     * @return string
     */
    protected function joinParams(array $params)
    {
        $string = "";
        foreach($params as $key => $value){
            $string .= $key."=".$value."&";
        }

        return trim($string,"&");
    }

}
