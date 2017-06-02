<?php

/**
 * 菜单管理
 * Created by PhpStorm.
 * User: ken
 * Date: 2017/4/7
 * Time: 下午10:39
 */

namespace wechat\build;

use wechat\WeChat;

class Button extends WeChat
{

    /**
     * 创建菜单
     * @param string $button 菜单数据
     * @return array|mixed
     */
    public function create($button)
    {
        $url = $this->apiUrl . '/cgi-bin/menu/create?access_token=' . $this->getAssessToken();
        $content = $this->curl($url, $button);
        return $this->get($content);
    }

    /**
     * 查询菜单
     * @return array|mixed
     */
    public function query()
    {
        $url = $this->apiUrl . '/cgi-bin/menu/get?access_token=' . $this->getAssessToken();
        $content = $this->curl($url);
        return $this->get($content);
    }

    /**
     * 删除菜单
     * @return array|mixed
     */
    public function delete()
    {
        $url = $this->apiUrl . '/cgi-bin/menu/delete?access_token=' . $this->getAssessToken();
        $content = $this->curl($url);
        return $this->get($content);
    }


    /**
     * 创建个性化菜单
     * @param string $button 菜单数据
     * @return array|mixed
     */
    public function createConditional($button)
    {
        $url = $this->apiUrl . '/cgi-bin/menu/addconditional?access_token=' . $this->getAssessToken();
        $content = $this->curl($url, $button);
        return $this->get($content);
    }

}