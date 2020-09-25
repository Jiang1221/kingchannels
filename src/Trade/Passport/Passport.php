<?php


namespace Jzb\Trade\Passport;


use Jzb\Common\Helper;

class Passport
{
    /**
     * 账号密码登录
     * @param string $account 账号（手机号或邮箱）
     * @param string $password 密码（明文）
     * @return bool|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login($account,$password)
    {
        $param = ['number' => $account, 'password' => $password];
        $apiName = '/passport/net/passport/login';
        $res = Helper::guzzleRequest($apiName, 'POST', $param);
        return $res;
    }
}