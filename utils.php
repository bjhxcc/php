<?php

/**
 * @Author: dreamsun
 * @Date: 2019年11月13日
 * @Time: 下午1:48:38
 */
/**
 * 获取客户端IP地址
 *
 * @param integer $type
 *            返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function getClientIp($type = 0)
{
    $type = $type ? 1 : 0;
    $ip = null;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array(
        $ip,
        $long
    ) : array(
        '0.0.0.0',
        0
    );
    return $ip[$type];
}
// echo getClientIp();
/**
 * 多长时间前的时间函数
 *
 * @param unknown $dateline            
 * @return boolean|string
 */
function timeFromNow($dateline)
{
    if (empty($dateline)) {
        return false;
    }
    $seconds = time() - $dateline;
    if ($seconds < 60 && $seconds > 0) {
        return "1分钟前";
    } elseif ($seconds < 3600 && $seconds > 0) {
        return floor($seconds / 60) . "分钟前";
    } elseif ($seconds < 24 * 3600 && $seconds > 0) {
        return floor($seconds / 3600) . "小时前";
    } elseif ($seconds < 48 * 3600 && $seconds > 0) {
        return date("昨天 H:i", $dateline) . "";
    } else {
        return date('Y-m-d', $dateline);
    }
}
// echo timeFromNow(strtotime("2019/11/13 14:48:34")) . '<br/>';
/**
 *
 * alert提示
 *
 * @param unknown $msg            
 */
function alert($msg)
{
    echo "<script>alert('$msg');</script>";
}
//echo alert('dd');