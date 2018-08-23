<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/4
 * Time: 19:28
 */

namespace app\modules\weixin\controllers;


use app\common\components\BaseWebController;
use app\common\components\HttpClient;
use app\common\services\UrlService;
use app\common\services\weixin\RequestService;

class MenuController extends BaseWebController
{

    public function actionSet()
    {

        $menu = [
            "button" => [
                [
                    'name' => '商城',
                    'type' => 'view',
                    'url' => UrlService::buildMUrl("/default/index")
                ],
                [
                    'name' => '我',
                    'type' => 'view',
                    'url' => UrlService::buildMUrl("/user/index")
                ],
            ]
        ];
        $config = \Yii::$app->params['weixin'];
        RequestService::setConfig($config['appid'], $config['token'], $config['sk']);
        $access_token = RequestService::getAccessToken();
        if ($access_token) {
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
            $ret = HttpClient::post($url, json_encode($menu, JSON_UNESCAPED_UNICODE));
            var_dump($ret);
        }
    }
}