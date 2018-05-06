<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/6
 * Time: 15:19
 */

namespace app\common\services;


class BaseService
{
    public static $err_msg = null;
    public static $err_code = null;

    public static function _err($msg, $code=-1)
    {
        self::$err_msg = $msg;
        self::$err_code = $code;
        return false;
    }

    public static function getLastErrorMsg()
    {

        return self::$err_msg;
    }

    public static function getLastErrorCode()
    {
        return self::$err_code;
    }

}