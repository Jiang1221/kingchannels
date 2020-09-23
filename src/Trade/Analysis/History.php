<?php
namespace Jzb\Trade\Analysis;


use Jzb\Common\Helper;

/**
 * Class History.
 * 历史日志相关接口控制器
 */
class History
{
    /**
     * 历史列表查询
     * @param int $ps 每页查询数量
     * @param int $cp 页码
     * @param array $objectIds 对象Ids 示例: [1,2.3]
     * @param array $skuIds 资源skuIds 示例: [1,2.3]
     * @param array $actionTypeIds 操作类型Ids 示例: [1,2.3]
     * @param array $objectTypeIds 对象类型Ids 示例: [1,2.3]
     * @param array $organizationIds 机构Ids 示例: [1,2.3]
     * @param string $startTime 开始时间 示例: 2020-09-10 00:00:00 时间筛选在起始时间都存在时才有效
     * @param string $endTime 结束时间 示例: 2020-09-20 23:59:59 时间筛选在起始时间都存在时才有效
     * @param array $userIds 用户Ids（针对管理员有效）
     * @param bool $isSelf 是否只查询自己的历史记录（针对管理员有效）
     * @param string $sortFieldName 排序字段：id/createTime
     * @param string $sortType 排序类型：desc-降序（默认） asc-升序
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listSearch(
        $ps = 10,
        $cp = 1,
        $objectIds = [],
        $skuIds = [],
        $actionTypeIds = [],
        $objectTypeIds = [],
        $organizationIds = [],
        $startTime = '',
        $endTime = '',
        $userIds = [],
        $isSelf = true,
        $sortFieldName = 'id',
        $sortType = 'desc')
    {
        $apiName = '/analysis/php/history/list';
        $param = [
            'ps' => $ps,
            'cp' => $cp,
            'objectIds' => implode(',', array_filter(array_unique($objectIds))),
            'skuIds' => implode(',', array_filter(array_unique($skuIds))),
            'actionTypes' => implode(',', array_filter(array_unique($actionTypeIds))),
            'objectTypes' => implode(',', array_filter(array_unique($objectTypeIds))),
            'organizationIds' => implode(',', array_filter(array_unique($organizationIds))),
            'startTime' => $startTime,
            'endTime' => $endTime,
            'userIds' => implode(',', array_filter(array_unique($userIds))),
            'isSelf' => $isSelf,
            'sortFieldName' => $sortFieldName,
            'sortType' => $sortType,
        ];

        $res = Helper::guzzleRequest($apiName, 'GET', $param);
        return 1;

    }

}
