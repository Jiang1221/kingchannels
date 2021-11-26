<?php
namespace Jzb\Common;

use GuzzleHttp\Client;
use function GuzzleHttp\Promise\is_settled;

class Helper
{
    public static function request(string $apiName, $method = 'GET', array $param = [], $timeout = 10)
    {
        if (empty($url)) return false;

        // 拼接完整地址
        $url = Config::$appInfo['gatewayUrl'] . $apiName;

        // 补充公共参数
        $param = self::getPublicParam($param);

        return self::guzzleRequest($url, $method, $param, $timeout);
    }


    /**
     * 使用guzzle请求
     * @param string $url 请求地址
     * @param string $method 请求方式
     * @param array $param 请求参数
     * @param int $timeout 请求超时时间
     * @param bool $toArray 是否将请求结果转为数组：true-是（默认） false-否
     * @return bool|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function guzzleRequest(
        string $url,
        $method = 'GET',
        array $param = [],
        $timeout = 10,
        $toArray = true)
    {
        // 是否在支持的请求方式内
        $methods = ['GET', 'POST'];
        $method = strtoupper($method);
        if (!in_array($method, $methods)) return false;

        //参数不为空
        if (!empty($param)) {
            $param = self::generateToken($param); // 生成签名

            // 按请求方式组装请求参数
            $param = $method == 'GET' ? ['query' => $param] : ['form_params' => $param];
        }

        // 使用guzzle 发起请求
        try {
            $client = new Client(['verify' => false, 'timeout' => $timeout, 'http_errors' => false]); // https请求不验证cert
            $response = $client->request($method, $url, $param);
            $result = $response->getBody()->getContents();

            // 返回请求结果
            if($toArray == false) return  $result;
            return self::setResult($result);

        } catch (\Exception $e) {
            return [
                'Success' => false,
                'code' => 0,
                'description' => $e->getMessage(),
                'data' => null,
            ];
        }

    }


    /**
     * 处理结果
     */
    public static function setResult($result)
    {
        $data = [
            'Success' => true,
            'Code' => 200,
            'Description' => '',
            'Data' => null,
        ];

        // json转数组
        $res = json_decode($result, true);
        if (is_null($res)) {
            $data['Description'] = '无法解析返回结果';
            $data['Data'] = $result;
            return $data;
        }

        if(isset($res['Success'])) $data['Success'] = $res['Success'];
        if(isset($res['Code'])) $data['Code'] = $res['Code'];
        if(isset($res['Description'])) $data['Description'] = $res['Description'];
        if(isset($res['Data'])) $data['Data'] = $res['Data'];
        return $data;
    }


    /**
     * 补充公共参数
     * @param $param
     * @return array
     * @throws MyException
     */
    public static function getPublicParam($param)
    {
        $clientTimezone = date_default_timezone_get(); // 获取用户当前配置的时区 用户没配置时 默认是UTC时间
        if($clientTimezone != 'UTC') date_default_timezone_set('UTC'); // 使用0时区时间
        $time = date('Y-m-d H:i:s');
        if($clientTimezone != 'UTC') date_default_timezone_set($clientTimezone); // 改回用户自己设置的时区

        // 从配置文件中取相关参数
        $publicParam = [
            'X_Public_AppId' => Config::$appInfo['appId'],
            'X_Public_ApiVersion' => Config::$appInfo['apiVersion'],
            'X_Public_AppVersion' => Config::$appInfo['apiVersion'],
            'X_Public_TimeStamp' => $time,
            'X_Public_Nonce' => round(0 + mt_rand() / mt_getrandmax() * (1 - 0),16), // 唯一随机32位字符串
        ];

        // 获取deviceToken及accessToken
        $publicParam['X_Public_DeviceToken'] = self::getDeviceToken();
        $publicParam['X_Public_AccessToken'] = self::getAccessToken();

        return array_merge($param, $publicParam);
    }

    /**
     * 获取deviceToken
     */
    private function getDeviceToken(){

        // 从缓存文件中读取一次
        $path = '__DIR__'.'/cache';
        $handle = fopen($path, '+w');
        $info = fread($handle, filesize($path));
        if(!empty($info)) $info = json_decode($info, true);

        // 缓存文件中没有时 调接口绑定设备
        if(empty($info) || !isset($info['deviceToken']) || empty($info['deviceToken'])){
            $apiName = Config::$appInfo['gatewayUrl'].'/passport/net/device/create';
            $deviceToken = uniqid();
            $dataJson = json_encode(['DeviceToken' =>$deviceToken], JSON_UNESCAPED_UNICODE);
            $param = ['dataJson' => $dataJson];
            $param = self::getPublicParam($param);
            $param = self::generateToken($param);

            $res = Helper::guzzleRequest($apiName, 'POST');
            if (isset($res['Success']) && $res['Success'] == true) {
                $info['deviceToken'] = $deviceToken;
                fwrite($handle, json_encode($info, JSON_UNESCAPED_UNICODE));
                fclose($handle);
                return $deviceToken;
            }

            throw new MyException('绑定设备失败!');

        }else{

            return $info['deviceToken'];
        }

    }

    /**
     * 获取accessToken
     */
    private function getAccessToken(){
        // 从缓存文件中读取一次
        $path = '__DIR__'.'/cache';
        $handle = fopen($path, '+w');
        $info = fread($handle, filesize($path));
        if(!empty($info)) $info = json_decode($info, true);

        if(empty($info) || !isset($info['accessToken']) || empty($info['accessToken'])){
            $account = Config::$userInfo['account'];
            $password = Config::$userInfo['password'];
            $param = ['number' => $account, 'password' => $password];
            $apiName = '/passport/net/passport/login';
            $res = Helper::guzzleRequest($apiName, 'POST', $param);
            if (isset($res['Success']) && $res['Success'] == true && isset($res['Data']['X_Public_AccessToken'])) {
                $info['accessToken'] = $res['Data']['X_Public_AccessToken'];
                fwrite($handle, json_encode($info, JSON_UNESCAPED_UNICODE));
                fclose($handle);
                return $info['accessToken'];
            }

            $errString = '账号：'.Config::$appInfo['account'].'，密码：'.Config::$appInfo['password'].'登录失败!';
            throw new MyException($errString);

        }else{

            return $info['accessToken'];
        }

    }


    /**
     * 根据参数生成签名
     * @param array $param 请求参数
     * @return array|bool
     */
    public static function generateToken(array $param)
    {
        if (empty($param) || !is_array($param)) return false;

        // 按参数名自然排序（不区分大小写升序排序）
        ksort($param, SORT_NATURAL | SORT_FLAG_CASE);

        $str = '';
        foreach ($param as $key => $value) {
            $str .= $key . '=' . $value;
        }

        // 拼接应用秘钥
        $str .= Config::$appInfo['appSecret'];

        // sha1加密后的字符串为公共参数X_Public_Token的值
        $param['X_Public_Token'] = sha1($str);

        return $param;
    }
}

