<?php

/**
 * 用户管理
 * Created by PhpStorm.
 * User: ken
 * Date: 2017/4/8
 * Time: 下午1:06
 */

namespace wechat\build;

use wechat\WeChat;

class User extends WeChat
{

    /**
     * 设置设备名称
     * @param array $param
     * @return array|mixed
     */
    public function setRemark($param)
    {
        $url = $this->apiUrl . '/cgi-bin/user/info/updateremark?access_token=' . $this->getAssessToken();
        $content = $this->curl($url, json_encode($param, JSON_UNESCAPED_UNICODE));
        return $this->get($content);
    }

    /**
     * 获取用户基本信息
     * @param string $openid   用户编号(微信服务器)关注时获取的
     * @param string $lang 语言 默认简体中文
     * @return array|mixed
     */
    public function getUserInfo($openid, $lang = 'zh_CN')
    {
        $url = $this->apiUrl . '/cgi-bin/user/info?access_token=' . $this->getAssessToken() . '&openid=' . $openid . '&lang=' . $lang;
        $content = $this->curl($url);
        return $this->get($content);
    }

    /**
     * 批量获取用户基本信息
     * @param array $param
     * @return array|mixed
     */
    public function getUserInfoLists($param)
    {
        $url = $this->apiUrl . 'cgi-bin/user/info/batchget?access_token=' . $this->getAssessToken();
        $content = $this->curl($url, json_encode($param, JSON_UNESCAPED_UNICODE));
        return $this->get($content);
    }

    /**
     * 获取用户列表
     * @param string $next_openid 第一个拉取的openid，不填默认从头开始拉取
     * @return array|mixed
     */
    public function getUserLists($next_openid = '')
    {
        $url = $this->apiUrl . '/cgi-bin/user/get?access_token=' . $this->getAssessToken() . '&next_openid=' . $next_openid;
        $content = $this->curl($url);
        return $this->get($content);
    }

}