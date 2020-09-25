<?php


namespace Jzb\Trade\Fulltext;


use Jzb\Common\Helper;

class TableOfContent
{
    /**
     * 获取sku（资源）目录
     * @param $skuId
     */
    public function list($skuId){
        $api = '/resource/php/tableofcontent/list';
        $res = Helper::guzzleRequest($api, 'GET', ['skuId' =>$skuId]);
    }
}