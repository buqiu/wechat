<?php

/**
 * 微信操作的基类
 * Created by PhpStorm.
 * User: ken
 * Date: 2017/4/4
 * Time: 下午2:06
 */

namespace wechat;

class WeChat extends Error
{
    //微信配置项
    static $config = [];

    //API根地址
    protected $apiUrl;

    //微信服务器发来的数据
    protected $message;

    //access_token
    protected $accessToken;

    /**
     * 初始化
     * WeChat constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            self::$config = $config;
        }
        //API根地址
        $this->apiUrl = 'https://api.weixin.qq.com';
        //处理 微信服务器 发来的数据
        $this->message = $this->parsePostRequestData();
    }

    /**
     * 微信接口整合验证进行绑定
     * @return bool
     */
    public function valid()
    {
        //只有以下这些get参数时，才是微信绑定服务器的行为
        if (!isset($_GET["echostr"]) || !isset($_GET["signature"]) || !isset($_GET["timestamp"]) || !isset($_GET["nonce"])) {
            return false;
        }
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = self::$config['token'];
        $tmpArr = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            echo $echoStr;
            exit;
        } else {
            return false;
        }
    }

    /**
     * 获取功能实例
     * @param $name //实例化的名称
     * @return mixed
     */
    public function instance($name)
    {
        $class = '\wechat\build\\' . ucfirst($name);
        return new $class;
    }

    /**
     * 发送请求，第二个参数有值时为post请求
     * @param string $url 请求地址
     * @param array $fields 发送的post表单
     * @return string
     */
    public function curl($url, $fields = [])
    {
        //初始化
        $ch = curl_init();
        //设置我们请求的地址
        curl_setopt($ch, CURLOPT_URL, $url);
        //数据返回后不要直接显示
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //禁止证书校验
        //对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //从证书中检查SSL加密算法算法存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($fields) {
            //请求超时时间
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            //POST 请求
            curl_setopt($ch, CURLOPT_POST, 1);
            //POST 变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        $data = '';
        if (curl_exec($ch)) {
            //发送成功,获取数据
            $data = curl_multi_getcontent($ch);
        }
        curl_close($ch);

        return $data;
    }

    /**
     * 获取AssessToken
     * access_token是公众号的全局唯一票据，公众号调用各接口时都需使用access_token。开发者需要进行妥善保存。access_token的存储
     * 至少要保留512个字符空间。access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效，
     * 每天可获取2000次
     * 服务器返回的 access_token 过期时间，一般2小时
     * @param bool $force 强制获取
     * @return bool
     */
    public function getAssessToken($force = false)
    {
        //缓存名
        $cacheName = md5(self::$config['appID'] . self::$config['appsecret']);
        //缓存文件
        $file = __DIR__ . '/cache/' . $cacheName . '.php';
        if ($force === false && is_file($file) && filemtime($file) + 7000 > time()) {
            //缓存有效
            $data = include $file;
        } else {
            $url = $this->apiUrl . '/cgi-bin/token?grant_type=client_credential&appid=' . self::$config['appID'] . '&secret=' . self::$config['appsecret'];
            $data = json_decode($this->curl($url), true);
            //获取失败
            if (isset($data['errcode'])) {
                return false;
            }
            //缓存access_token
            $dir = dirname($file);
            is_dir($dir) || mkdir($dir, 0755, true);
            file_put_contents($file, '<?php return ' . var_export($data, true) . ';');
        }

        //获取assess_token成功
        return $this->accessToken = $data['access_token'];
    }

    /**
     * 获取微信服务器发来的消息（官网消息或用户消息)
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 解析微信发来的POST/XML数据
     */
    private function parsePostRequestData()
    {
        if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            return simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);
        }
    }

}