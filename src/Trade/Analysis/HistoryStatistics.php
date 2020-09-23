<?php


namespace Jzb\Trade\Analysis;


use Jzb\Common\Helper;

class HistoryStatistics
{
    /**
     * @param int $actionType
     * @param string $startTime
     * @param string $endTime
     * @param int $dcdId
     * @param int $organizationId
     * @param array $objectTypes
     * @param array $appIds
     * @param int $ps
     * @param array $relationObjectIds
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function objectRank(
        int $actionType = null,
        string $startTime = null,
        string $endTime = null,
        int $dcdId = null ,
        int $organizationId = null,
        array $objectTypes=[],
        array $appIds = [],
        $limit = 100,
        array $relationObjectIds = [])
    {
        if ($limit > 10000) $limit = 10000;
        $param['ps'] = $limit;
        if(!is_null($actionType)) $param['actionType'] = $actionType;
        if(!is_null($startTime) && is_null($endTime)) {
            $param['startTime'] = $startTime;
            $param['endTime'] = $endTime;
        }
        if(!is_null($dcdId)) $param['dcdId'] = $dcdId;
        if(!is_null($organizationId)) $param['organizationId'] = $organizationId;
        if(!empty($objectTypes)) $param['objectTypes'] = implode(',', $objectTypes);
        if(!empty($appIds)) $param['appIds'] = implode(',', $appIds);

        $apiName = '/analysis/php/historystatistics/objectrank';
        $res = Helper::guzzleRequest($apiName, 'GET', $param);
        return $res;
    }
}