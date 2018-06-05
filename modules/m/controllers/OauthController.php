<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5
 * Time: 18:14
 */

namespace app\modules\m\controllers;


use app\common\components\HttpClient;
use app\common\services\UrlService;
use app\modules\m\controllers\common\BaseController;

class OauthController extends BaseController
{

    public function actionLogin(){
        $scope = $this->get('scope','snsapi_base');
        $appid = \Yii::$app->params['weixin']['appid'];
        $redirect_url = UrlService::buildMUrl('/oauth/callback');
        $url =
            "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_url}&response_type=code&scope={$scope}&state=#wechat_redirect";
        echo $url;exit();
    }

    public function actionCallback(){

        $code = $this->get('code','');
        if(!$code){
            return $this->goHome();
        }
        //通过code 获取access_token
        $appid =  \Yii::$app->params['weixin']['appid'];
        $sk = \Yii::$app->params['weixin']['sk'];
        $url =
            "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$sk}&code={$code}&grant_type=authorization_code";

        $ret = HttpClient::get($url);
        $ret = json_decode($ret,true);
        $ret_token = isset($ret['access_token']) ? $ret['access_token'] : '';
        if(!$ret){
            return $this->goHome();
        }
        $openid = isset($ret['openid']) ? $ret['openid'] : '';
        $scope = isset($ret['scope']) ? $ret['scope'] : '';
        if($scope == 'snsapi_userinfo'){
            $url =  "https://api.weixin.qq.com/sns/userinfo?access_token={$ret_token}&openid={$openid}&lang=zh_CN";
            $wechat_user_info = HttpClient::get($url);
            var_dump($wechat_user_info);
        }

    }
}