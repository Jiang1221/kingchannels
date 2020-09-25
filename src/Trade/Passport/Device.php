<?php


namespace Jzb\Trade\Passport;


use Jzb\Common\Helper;

/**
 * Class Device --设备相关操作
 * @package Jzb\Trade\Passport
 */
class Device
{
    /**
     * 创建设备记录
     * @param string $deviceToken （必须）设备唯一标识
     * @param string|null $title 设备名称
     * @param string|null $umengToken 友盟设备编号
     * @param int|null $deviceTypeId 设备类型Id
     * @param int|null $screenWidth 屏幕宽度PX
     * @param int|null $screenHeight 屏幕高度PX
     * @param int|null $dpiX 屏幕横向密度
     * @param int|null $dpiY 屏幕纵向密度
     * @param string|null $osName 系统名称
     * @return bool|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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