<?php
/**
 * User: szliugx@gmail.com
 * Date: 17/8/5
 * Time: 上午11:46
 */

namespace WenXi\TtsApi\Platforms;

use WenXi\TtsApi\Contracts\PlatformInterface;
use WenXi\TtsApi\Exceptions\PlatformErrorException;
use WenXi\TtsApi\Support\Config;
use WenXi\TtsApi\Traits\HasHttpRequest;

class BaiduPlatform implements PlatformInterface
{
    use HasHttpRequest;

    const ACCESS_TOKEN_URL = 'https://openapi.baidu.com/oauth/2.0/token';
    const GRANT_TYPE = 'client_credentials';
    const REQUEST_URL = 'http://tsn.baidu.com/text2audio';

    public function send($text, Config $config)
    {
        ////获取语音参数
        //tex	必填	合成的文本，使用UTF-8编码，请注意文本长度必须小于1024字节
        //lan	必填	语言选择,目前只有中英文混合模式，填写固定值zh
        //tok	必填	开放平台获取到的开发者 access_token
        //ctp	必填	客户端类型选择，web端填写固定值1
        //cuid	必填	用户唯一标识，用来区分用户，计算UV值。建议填写能区分用户的机器 MAC 地址或 IMEI 码，长度为60字符以内。
        //spd	选填	语速，取值0-9，默认为5中语速
        //pit	选填	音调，取值0-9，默认为5中语调
        //vol	选填	音量，取值0-15，默认为5中音量
        //per	选填	发音人选择, 0为普通女声，1为普通男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女声
        $params = [];
        $params['tex'] = $text;
        $params['lan'] = empty($config->get('lan')) ? 'zh' : $config->get('lan');
        $params['tok'] = $this->getAccessToken($config->get('client_id'), $config->get('client_secret'));
        $params['ctp'] = empty($config->get('ctp')) ? 1 : $config->get('ctp');
        $params['cuid'] = empty($config->get('cuid')) ? md5($text) : $config->get('cuid');
        $params['spd'] = is_null($config->get('spd')) ? 5 : $config->get('spd');
        $params['pit'] = is_null($config->get('pit')) ? 5 : $config->get('pit');
        $params['vol'] = is_null($config->get('vol')) ? 5 : $config->get('vol');
        $params['per'] = is_null($config->get('per')) ? 0 : $config->get('per');

        $response = $this->post(self::REQUEST_URL, $params);

        return $response;
    }

    /**
     * 获取access_token
     * @param $client_id
     * @param $client_secret
     */
    protected function getAccessToken($client_id, $client_secret)
    {
        ////获取access_token参数
        //grant_type：必须参数，固定为“client_credentials”；
        //client_id：必须参数，应用的 API Key；
        //client_secret：必须参数，应用的 Secret Key;
        $params = [
            'grant_type' => self::GRANT_TYPE,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ];
        $response = $this->post(self::ACCESS_TOKEN_URL, $params);

        if(empty($response['access_token'])){
            throw new PlatformErrorException('从百度授权获取access_token失败.'.json_encode($params), 501, $response);
        }

        return $response['access_token'];
    }

}
