<?php
namespace Jzb\Common;

class Config{

    public static $appInfo = [
        'appId' => '14', // 您的应用Id
        'appSecret' => 'flowerh589xJSAAWZE', // 您的应用秘钥
        'apiVersion' => '1.0', // 接口版本号
        'appVersion' => '1.0.0', // 应用版本号
        'gatewayUrl' => 'http://develop.kingchannels.cn:50112/transfer', // 网关地址
        'account' => '',
        'password' => '',
    ];

    public static $userInfo = [
        'account' => '', // 渠道登录账号
        'password' => '', // 渠道登录密码
    ];

}

