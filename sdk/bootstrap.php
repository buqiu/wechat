<?php

/**
 * 自动加载类
 * php > 5.4
 * Created by PhpStorm.
 * User: ken
 * Date: 2017/4/4
 * Time: 上午11:11
 */

function __autoload($class)
{
    include str_replace('\\', '/', $class) . '.php';
}

(new \app\Entry())->handler();