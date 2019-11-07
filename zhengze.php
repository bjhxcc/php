<?php
/**
 * @Author: dreamsun
 * @Date: 2019年11月7日
 * @Time: 下午1:52:12
 */
// 检查http或https正则
$url = "http://www.baidu.com";
$preg = "/^http(s)?:\\/\\/.+/";
if (preg_match($preg, $url)) {
    echo true;
} else {
    echo false;
}
echo date("Y-m-d H:i:s",time());