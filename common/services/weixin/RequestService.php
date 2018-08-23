<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/7/5
 * Time: 下午7:40
 */
namespace app\common\services\weixin;


use app\common\components\HttpClient;
use app\common\services\BaseService;
use app\models\member\OauthAccessToken;

class RequestService extends BaseService
{
    private static $url = 'https://api.weixin.qq.com/cgi-bin/';
    private static $appid = '';
    private static $app_token = '';
    private static $secret = '';

    public static function getAccessToken(){

        $date_now = date('Y-m-d H:i:s');

        $access_token_info = OauthAccessToken::find()->where(['>', 'expired_time', $date_now])->limit(1)->one();
        if ($access_token_info) {
            return $access_token_info['access_token'];
        }

        //获取token

        $url = self::$url.'token?grant_type=client_credential&appid='.self::$appid.'&secret='.self::$secret.'';


        $res =  HttpClient::get($url);
        $ret = json_decode($res,true);
        if(!$ret || isset($ret['errcode'])){

            return self::_err($ret['errcode']);
        }

        $model_access_token = new OauthAccessToken();
        $model_access_token->access_token = $ret['access_token'];
        $model_access_token->expired_time = date("Y-m-d H:i:s", $ret['expires_in'] + time() - 200);
        $model_access_token->created_time = $date_now;
        $model_access_token->save(0);
        return $ret['access_token'];

    }


    public static function setConfig($appid,$app_token,$secret){

        self::$appid = $appid;
        self::$secret = $secret;
        self::$app_token = $app_token;
    }


}