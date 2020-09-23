<?php
namespace Jzb\Common;

use GuzzleHttp\Client;
use function GuzzleHttp\Promise\is_settled;

class Helper
{

    /**
     * 使用guzzle请求
     * @param string $url 请求地址
     * @param string $method 请求方式
     * @param array $param 请求参数
     * @param int $timeout 请求超时时间
     * @return bool|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function guzzleRequest(string $url, $method = 'GET', array $param = [], $timeout = 10)
    {
        if (empty($url)) return false;
        $finalUrl = Config::$appInfo['gatewayUrl'].$url;

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
            $response = $client->request($method, $finalUrl, $param);
            $result = $response->getBody()->getContents();

            // 返回请求结果
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

        $res = json_decode($result, true);
        if (is_null($res)) {
            $data['Description'] = '无法解析返回结果';
            $data['Data'] = '无法解析返回结果';
            $data['Description'] = $result;
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
     */
    public static function getPublicParam($param)
    {

        $publicParam = [
            'X_Public_AppId' => Config::$appInfo['appId'],
            'X_Public_ApiVersion' => Config::$appInfo['apiVersion'],
            'X_Public_AppVersion' => Config::$appInfo['apiVersion'],
            'X_Public_TimeStamp' => date('Y-m-d H:i:s'),
//            'X_Public_DeviceToken' => '',
//            'X_Public_AccessToken' => '',
        ];

        return array_merge($param, $publicParam);
    }


    /**
     * 根据参数生成签名
     * @param array $param 请求参数
     * @return array|bool
     */
    public static function generateToken(array $param)
    {
        if (empty($param) || !is_array($param)) return false;

        // 补充公共参数
        $param = self::getPublicParam($param);

        // 按参数名自然排序（不区分大小写升序排序）
        ksort($param, SORT_NATURAL | SORT_FLAG_CASE);

        $str = '';
        foreach ($param as $key => $value) {
            $str .= $key . '=' . $value;
        }

        // 拼接应用秘钥
//        $str .= Config::$appInfo['appSecret'];

        // sha1加密后的字符串为公共参数X_Public_Token的值
        $param['X_Public_Token'] = sha1($str);

        return $param;
    }
}

