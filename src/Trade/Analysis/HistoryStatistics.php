<?php


namespace Jzb\Trade\Analysis;


use Jzb\Common\Helper;

class HistoryStatistics
{
    /**
     * @param int $actionTypeId 操作类型Id
     * @param string $startTime 开开始时间 示例: 2020-09-10 00:00:00 时间筛选在起始时间都存在时才有效
     * @param string $endTime 结束时间 示例: 2020-09-20 23:59:59 时间筛选在起始时间都存在时才有效
     * @param int $dcdId 出版社Id
     * @param int $organizationId 机构Id
     * @param array $objectTypeIds 资源类型
     * @param int $limit 查询排行榜前多少名 默认100，最多1万
     * @param array $relationObjectIds 关联资源Id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function objectRank(
        int $actionTypeId = null,
        string $startTime = null,
        string $endTime = null,
        int $dcdId = null,
        int $organizationId = null,
        array $objectTypeIds = [],
        $limit = 100,
        array $relationObjectIds = [])
    {
        if ($limit > 10000) $limit = 10000;
        $param['ps'] = $limit;
        if (!is_null($actionTypeId)) $param['actionType'] = $actionTypeId;
        if (!is_null($startTime) && is_null($endTime)) {
            $param['startTime'] = $startTime;
            $param['endTime'] = $endTime;
        }
        if (!is_null($dcdId)) $param['dcdId'] = $dcdId;
        if (!is_null($organizationId)) $param['organizationId'] = $organizationId;
        if (!empty($objectTypeIds)) $param['objectTypes'] = implode(',', $objectTypeIds);
        if (!empty($appIds)) $param['appIds'] = implode(',', $appIds);
        if (!empty($relationObjectIds)) $param['relationObjectIds'] = implode(',', $relationObjectIds);

        $apiName = '/analysis/php/historystatistics/objectrank';
        $res = Helper::guzzleRequest($apiName, 'GET', $param);
        return $res;
    }
}