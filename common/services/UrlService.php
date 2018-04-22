<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/15
 * Time: 14:55
 */

namespace app\common\services;

//构建统一的链接管理器的地址
use yii\helpers\Url;

class UrlService
{
    public static function buildWebUrl($path,$params=[]){
        $domain = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path],$params));
        return $domain['web'].$path;
    }


    public static function buildMUrl($path,$params=[]){
        $domain = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path],$params));
        return $domain['m'].$path;
    }

    //构建官网的连接
    public static function buildWwwUrl($path,$params=[]){
        $domain = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path],$params));
        return $domain['www'].$path;
    }

    //构建一个空链接
    public static function buildNullUrl(){
        return "javascript:void(0);";
    }
}