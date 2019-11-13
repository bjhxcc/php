<?php
header("Content-type:text/html;charset=utf-8");

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
// echo alert('dd');
/**
 * 列出目录内容
 *
 * @param unknown $dir            
 */
function listFiles($dir)
{
    if (is_dir($dir)) {
        if (opendir($dir)) {
            $handle = opendir($dir);
            while (($file = readdir($handle)) !== false) {
                if ($file != "." && $file != ".." && $file != "Thumbs.db") {
                    echo $dir . '/' . $file . $file . '<br>';
                }
            }
            closedir($handle);
        }
    }
}
// echo listFiles('D:/wamp/apps');
/**
 * api返回信息
 * 数据返回
 *
 * @param [int] $code
 *            [结果码 200:正常/4**数据问题/5**服务器问题]
 * @param [string] $msg
 *            [返回的提示信息]
 * @param [array] $data
 *            [返回的数据]
 * @return [string] [最终的json数据]
 *        
 */
function returnMsg($code, $msg = '', $data = [])
{
    /**
     * ********* 组合数据 **********
     */
    $return_data['code'] = $code;
    $return_data['msg'] = $msg;
    $return_data['data'] = $data;
    /**
     * ********* 返回信息并终止脚本 **********
     */
    echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
    die();
}
// echo returnMsg(200, '成功！');
/**
 * 对象 转 数组
 *
 * @param object $obj
 *            对象
 * @return array
 */
function object2array($object)
{
    $object = json_decode(json_encode($object), true);
    return $object;
}
// print_r(object2array((Object)['q']));

/**
 * 对象转json数组格式的字符串
 *
 * @param unknown $object            
 * @return mixed
 */
function obj2arr($object)
{
    $json_object = json_encode($object);
    $arr = json_decode($json_object, 1);
    return $arr;
}
// print_r(obj2arr((Object)['q']));
/**
 * 数组 转 对象
 *
 * @param array $arr
 *            数组
 * @return object
 */
function arr2obj($arr)
{
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object) arr2obj($v);
        }
    }
    return (object) $arr;
}
// print_r(arr2obj(['q']));
/**
 * 关键词高亮
 *
 * @param unknown $sString            
 * @param unknown $aWords            
 * @return boolean|mixed
 */
function highlight($sString, $aWords)
{
    if (! is_array($aWords) || empty($aWords) || ! is_string($sString)) {
        return false;
    }
    $sWords = implode('|', $aWords);
    return preg_replace('@\b(' . $sWords . ')\b@si', '<strong style="background-color:yellow">$1</strong>', $sString);
}
// echo highlight('ligang love u,do you know this?', array('love','this'));
/**
 * 手机号验证
 *
 * @param string $str            
 * @return bool
 */
function mobile($str)
{
    return preg_match("/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/", $str);
}

/**
 * 固定电话（严格）
 * 
 * @param unknown $str            
 * @return number
 */
function telephone($str)
{
    return preg_match("/^(0[0-9]{2}-)?([2-9][0-9]{7})$|^(0[0-9]{3}-)?([2-9][0-9]{6})$/", $str);
}

/**
 * 固定电话（非严格）
 * 
 * @param unknown $str            
 * @return number
 */
function simpletelephone($str)
{
    return preg_match("/^\d{11}$/", $str);
}

/**
 * 下发手机验证码 6位数字
 * 
 * @param unknown $str            
 * @return number
 */
function smsCode($str)
{
    return preg_match("/^\d{6}$/", $str);
}

/**
 * 验证邮政编码
 * 
 * @param unknown $str            
 * @return number
 */
function postCode($str)
{
    return preg_match("/^\d{6}$/", $str);
}

/**
 * EMAIL验证
 * 
 * @param unknown $str            
 * @return number
 */
function email($str)
{
    return preg_match("/^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/", $str);
}

/**
 * QQ验证
 * 
 * @param unknown $str            
 * @return number
 */
function qq($str)
{
    return preg_match("/^[1-9]\d{4,11}$/", $str);
}

/**
 * 用户密码
 *
 * @param string $str            
 * @return boolean
 */
function passwd($str)
{
    return preg_match("/^[a-zA-Z0-9]{6,20}$/", $str);
}

/**
 * 身份证号验证
 *
 * @param string $str            
 * @return boolean
 */
function idno($str)
{
    return preg_match("/(^\d{15}$)|(^\d{17}(\d|X)$)/", $str);
}

/**
 * 检查是一个合法的url
 * 
 * @param unknown $str            
 * @return boolean
 */
function url($str)
{
    $url = $str;
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 判断一个字符串是不是json
 * 
 * @param unknown $str            
 * @return boolean
 */
function isJson($str)
{
    return ! is_null(json_decode($str));
}