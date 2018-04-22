<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 19:17
 */

namespace app\common\services;


class StaticService
{
    public static function includeAppJsStatic($path, $depend)
    {
        self::includeAppStatic('js',$path,$depend);
    }

    public static function includeAppCssStatic($path, $depend)
    {
        self::includeAppCssStatic('css',$path,$depend);
    }

    protected static function includeAppStatic($type, $path, $depend)
    {

        $release_version = defined('RELEASE_VERSION') ? RELEASE_VERSION : time();
        $path = $path ."?ver=".$release_version;
        if ($type == 'css') {
            \Yii::$app->getView()->registerCssFile($path, ['depends' => $depend]);
        } else {
            \Yii::$app->getView()->registerJsFile($path, ['depends' => $depend]);
        }
    }
}