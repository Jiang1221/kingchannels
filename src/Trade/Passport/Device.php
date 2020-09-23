<?php


namespace Jzb\Trade\Passport;


use Jzb\Common\Helper;

/**
 * Class Device --设备相关操作
 * @package Jzb\Trade\Passport
 */
class Device
{
    public function create(
        string $deviceToken,
        string $title = null,
        string $umengToken = null,
        int $deviceTypeId = null,
        int $screenWidth = null,
        int $screenHeight = null,
        int $dpiX = null,
        int $dpiY = null,
        string $osName = null)
    {
        $param['DeviceToken'] =$deviceToken;
        if(!empty($title)) $param['Title'] = $title;
        if(!empty($umengToken)) $param['UmengToken'] = $umengToken;
        if(!is_null($deviceTypeId)) $param['DeviceTypeId'] = $deviceTypeId;
        if(!is_null($screenWidth)) $param['ScreenWidth'] = $screenWidth;
        if(!is_null($screenHeight)) $param['ScreenHeight'] = $screenHeight;
        if(!is_null($dpiX)) $param['DpiX'] = $dpiX;
        if(!is_null($dpiY)) $param['DpiY'] = $dpiY;
        if(!empty($osName)) $param['OsName'] = $osName;
        $param = ['dataJson' => json_encode($param, JSON_UNESCAPED_UNICODE)];

        $apiName = '/passport/net/device/create';
        $res = Helper::guzzleRequest($apiName, 'POST', $param);
        return $res;
    }
}