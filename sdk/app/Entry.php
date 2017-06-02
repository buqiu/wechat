<?php

/**
 * 微信SDK功能（业务代码）
 * Created by PhpStorm.
 * User: ken
 * Date: 2017/4/4
 * Time: 下午2:13
 */

namespace app;

use wechat\WeChat;

class Entry
{
    /**
     * @var WeChat
     */
    protected $wechat;

    /**
     * 初始化
     * Entry constructor.
     */
    public function __construct()
    {
        $config = [
            'token' => 'lamp',
            'appID' => '',
            'appsecret' => '',
        ];
        $this->wechat = new WeChat($config);
        //绑定
        $this->wechat->valid();
    }

    /**
     * 微信管理入口
     */
    public function handler()
    {

    }

}