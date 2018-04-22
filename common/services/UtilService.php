<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 14:48
 */

namespace app\common\services;


class UtilService
{

    public static function getIP(){

        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR']? $_SERVER['REMOTE_ADDR']: '';
    }

}