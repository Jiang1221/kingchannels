<?php


namespace Jzb\Trade\Fulltext;


use Jzb\Common\Helper;

/**
 * Class Fulltext 搜索引擎相关接口
 * @package Jzb\Trade\Fulltext
 */
class Fulltext
{
    /**
     * 资源列表
     */
    public function resourceList(
        int $ps = 10,
        int $cp = 1,
        string $sortFieldName = '',
        string $sortType = '',
        int $objectTypeId = null,
        int $aqrId = null)
    {
        $param = [
            'ps' => $ps,
            'cp' => $cp,
        ];
        if(!empty($sortFieldName)) $param['sortFieldName'] = $sortFieldName;
        if(!empty($sortType)) $param['sortType'] = $sortType;
        if(!empty($objectTypeId)) $param['objectTypeId'] = $objectTypeId;
        if(!empty($aqrId)) $param['aqrId'] = $aqrId;
        $apiName = '/fulltext/net/fulltext/resourcelist';
        $res = Helper::guzzleRequest($apiName, 'GET', $param);
        return $res;

    }
}