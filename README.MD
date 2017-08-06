<h1 align="center">tts api 大全</h1>

<p align="center">组合市面上tts语音合成API的一款工具</p>

## 代码使用前

```shell
$ composer require wenxi/tts-api
```

## 调用代码示例如下

```php
<?php
/**
 * User: szliugx@gmail.com
 * Date: 17/8/5
 * Time: 下午2:30
 */

header("Content-type: text/html; charset=utf-8");

include_once './vendor/autoload.php';
use WenXi\TtsApi\Tts;

$configs = [
    'jixianyuan' => [
        'apiKey'       => 'cfa5620888446****c939a176096b491',
        'apiSecretKey' => 'f35b13eb1b9a4****f742445368ecbdd',
        'expires'      => time() + 3600,
        'voice'        => 'cnmale',
        'format'       => 'MP3',
        'speed'        => 1,
    ],
    'baidu'      => [
        'client_id'     => 'BsUAmCwHCLQUU****hnoUAatpHtnHGlU',
        'client_secret' => 'HM3oWwefKKZbG****v1xkSF071bZy9ne',
        'lan'           => 'zh',
        'ctp'           => 1,
        'cuid'          => '',
        'spd'           => '',
        'pit'           => '',
        'vol'           => '',
        'per'           => '',
    ]
];

//$platformName = 'jixianyuan';
$platformName = 'baidu';
$config       = $configs[$platformName];

$tts = new Tts($config);

try {
    $response = $tts->send("hello,world!", $platformName);
    var_dump($response);
} catch (\WenXi\TtsApi\Exceptions\InvalidArgumentException $iex) {
    echo "tts参数异常\n";
    echo $iex->getMessage();
} catch (\WenXi\TtsApi\Exceptions\PlatformErrorException $pex) {
    echo "tts平台异常\n";
    echo $pex->getMessage();
} catch (\WenXi\TtsApi\Exceptions\Exception $ex) {
    echo "tts异常\n";
    echo $ex->getMessage();
} catch (Exception $ex) {
    echo "系统异常\n";
    echo $ex->getMessage();
}

```

