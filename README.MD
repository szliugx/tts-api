<h1 align="center">tts api</h1>

<p align="center">:calling: 组合市面上tts语音合成API的一款工具</p>

调用代码示例如下
```
<?php
/**
 * User: szliugx@gmail.com
 * Date: 17/8/5
 * Time: 下午2:30
 */

include_once './vendor/autoload.php';
use VinCi\TtsApi\Tts;

$config = [
    'apiKey' => 'apiKey',
    'apiSecret' => '123456',
    'expires' => time()+3600,
    'voice' => 'cnmale',
    'format' => 'MP3',
    'speed' => 1,
];

$tts = new Tts($config);
try{
    $response = $tts->send("hello,world!","jixianyuan");
    var_dump($response);
}catch (\VinCi\TtsApi\Exceptions\InvalidArgumentException $ie){
    echo "tts参数异常\n";
    echo $ie->getMessage();
}catch(\VinCi\TtsApi\Exceptions\Exception $e){
    echo "tts异常\n";
    echo $e->getMessage();
}catch(Exception $e){
    echo "系统异常\n";
    echo $e->getMessage();
}

```