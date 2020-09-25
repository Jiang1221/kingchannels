<?php


namespace Jzb\Trade;

use Jzb\Common\Config;

/**
 * 基础类型 生成deviceToken并保存
 * Class Base
 * @package Jzb\Trade
 */
class Base
{
    public static $deviceToken = '';
    public static $accessToken = '';

    public function __construct()
    {
        $config = Config::$appInfo;
        foreach ($config as $k=>$v){
            if(empty($v)) return '请完善配置信息:'.$k;
        }

        if(empty($this->deviceToken = '')){

        }


    }

}