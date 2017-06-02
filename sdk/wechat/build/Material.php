<?php

/**
 * 素材管理
 * Created by PhpStorm.
 * User: ken
 * Date: 2017/4/8
 * Time: 下午11:52
 */

namespace wechat\build;

use wechat\WeChat;

class Material extends WeChat
{
    /**
     * 新增素材
     * @param string $type 素材类型
     * @param string $filePath 文件路径
     * @param int $mediaType 1 临时 0 永久
     * @return array|mixed
     */
    public function upload($type, $filePath, $mediaType = 0)
    {
        if ($mediaType) {
            //临时素材
            $url = $this->apiUrl . '/cgi-bin/media/upload?access_token=' . $this->getAssessToken() . '&type=' . $type;
        } else {
            //永久素材
            $url = $this->apiUrl . '/cgi-bin/material/add_material?access_token=' . $this->getAssessToken() . '&type=' . $type;
        }

        $filePath = realpath($filePath);
        if (class_exists('\CURLFile')) {
            $fileData = [
                'media' => new \CURLFile(realpath($filePath))
            ];
        } else {
            $fileData = [
                'media' => '@' . realpath($filePath)
            ];
        }
        $content = $this->curl($url, $fileData);
        return $this->get($content);
    }

    /**
     * 获取临时素材
     * @param $mediaId 媒体ID
     * @param $file
     * @return int
     */
    public function download($mediaId, $file)
    {
        $url = $this->apiUrl . '/cgi-bin/media/get?access_token=' . $this->getAssessToken() . '&media_id=' . $mediaId;
        $content = $this->curl($url);
        $dir = dirname($file);
        is_dir($dir) || mkdir($dir, 0755, true);
        return file_put_contents($file, $content);
    }

    /**
     * 获取永久素材
     * @param $mediaId 媒体ID
     * @return array|mixed
     */
    public function getMaterial($mediaId)
    {
        $url = $this->apiUrl . 'cgi-bin/material/get_material?access_token=' . $this->getAssessToken();
        $json = '{"media_id": ' . $mediaId . '}';
        $content = $this->curl($url, $json);
        return $this->get($content);
    }

    /**
     * 删除永久素材
     * @param $mediaId 媒体ID
     * @return array|mixed
     */
    public function delete($mediaId)
    {
        $url = $this->apiUrl . '/cgi-bin/material/del_material?access_token=' . $this->getAssessToken();
        $json = '{"media_id": ' . $mediaId . '}';
        $content = $this->curl($url, $json);
        return $this->get($content);
    }

    /**
     * 新增永久图文素材
     * @param array $articles
     * @return array|mixed
     */
    public function addNews($articles)
    {
        $url = $this->apiUrl . '/cgi-bin/material/add_news?access_token=' . $this->getAssessToken();
        $content = $this->curl($url, json_encode($articles, JSON_UNESCAPED_UNICODE));
        return $this->get($content);
    }

    /**
     * 修改永久图文素材
     * @param array $articles
     * @return array|mixed
     */
    public function editNews($articles)
    {
        $url = $this->apiUrl . '/cgi-bin/material/update_news?access_token=' . $this->getAssessToken();
        $content = $this->curl($url, json_encode($articles, JSON_UNESCAPED_UNICODE));
        return $this->get($content);
    }

    /**
     * 获取素材总数
     * @return array|mixed
     */
    public function total()
    {
        $url = $this->apiUrl . '/cgi-bin/material/get_materialcount?access_token=' . $this->getAssessToken();
        $content = $this->curl($url);
        return $this->get($content);
    }

    /**
     * 获取素材列表
     * @param array $param
     * @return array|mixed
     */
    public function lists($param)
    {
        $url = $this->apiUrl . '/cgi-bin/material/batchget_material?access_token=' . $this->getAssessToken();
        $content = $this->curl($url, json_encode($param, JSON_UNESCAPED_UNICODE));
        return $this->get($content);
    }

}