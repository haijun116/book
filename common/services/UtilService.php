<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 14:48
 */

namespace app\common\services;


use yii\helpers\Html;

class UtilService
{

    public static function getIP()
    {

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : '';
    }

    public static function encode($display)
    {
        return Html::encode($display);
    }

    public static function getRootPath()
    {
        return dirname(\Yii::$app->vendorPath);
    }
}