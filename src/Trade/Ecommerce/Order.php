<?php


namespace Jzb\Trade\Ecommerce;


use Jzb\Common\Helper;

class Order
{
    /**
     * 创建选书订单
     * @param array $skuIds 资源skuId
     * @param int $type 类型 1-机构 2-个人（默认）3-机构下的渠道
     * @param int $purchaserId 购买者id
     * @return bool|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createChooseBookOrder(array $skuIds,int $type, int$purchaserId)
    {
        $param = [
            'skuIds' => implode(',', $skuIds),
            'type' => $type,
            'purchaserId' => $purchaserId,
        ];
        $apiName = '/ecommerce/php/order/createchoosebookorder';
        $res = Helper::guzzleRequest($apiName, 'GET', $param);
        return $res;

    }

}