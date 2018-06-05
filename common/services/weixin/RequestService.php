<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/3
 * Time: 14:52
 */

namespace app\common\services\weixin;

use app\common\components\HttpClient;
use app\common\services\BaseService;
use app\models\member\OauthAccessToken;

class RequestService extends BaseService
{
    private static $app_token = '';
    private static $appid = '';
    private static $app_secret = '';
    private static $url = 'https://api.weixin.qq.com/cgi-bin/';

    public static function getAccessToken()
    {

        $date_now = date('Y-m-d H:i:s');
        $access_token_info = OauthAccessToken::find()->where(['>', 'expired_time', $date_now])->limit(1)->one();
        if ($access_token_info) {
            return $access_token_info['access_token'];
        }
        //调取接口

        $path = 'token?grant_type=client_credential&appid=' . self::getAppid() . '&secret=' . self::getAppSecret() . '';

        $res = self::send($path);

        if (!$res) {
            return self::_err(self::getLastErrorMsg());
        }

        $model_access_token = new OauthAccessToken();
        $model_access_token->access_token = $res['access_token'];
        $model_access_token->expired_time = date("Y-m-d H:i:s", $res['expires_in'] + time() - 200);
        $model_access_token->created_time = $date_now;
        $model_access_token->save(0);
        return $res['access_token'];
    }

    public static function send($path, $data = [], $method = 'get')
    {
        $request_url = self::$url . $path;
        if ($method == 'post') {
            $res = HttpClient::post($request_url, $data);

        } else {

            $res = HttpClient::get($request_url);
        }
        $ret = json_decode($res, true);

        if (!$ret || (isset($ret['errcode']) && $ret['errcode'])) {
            return self::_err($ret['errmsg']);
        }

        return $ret;

    }

    public static function setConfig($appid, $app_token, $app_secret)
    {
        self::$appid = $appid;
        self::$app_token = $app_token;
        self::$app_secret = $app_secret;
    }

    public static function getAppToken()
    {
        return self::$app_token;
    }


    public static function getAppid()
    {
        return self::$appid;
    }

    public static function getAppSecret()
    {
        return self::$app_secret;
    }

}