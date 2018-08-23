<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/8/12
 * Time: 下午4:06
 */

namespace app\common\services\weixin;


use app\common\components\HttpClient;
use app\common\services\BaseService;
use app\common\services\UrlService;
use app\models\member\OauthMemberBind;
use app\models\pay\PayOrder;

class TemplateService extends BaseService
{


    public static function payNotice($pay_order_id)
    {

        $pay_order_info = PayOrder::find()->where(['id' => $pay_order_id])->one();
        if (!$pay_order_info) {
            return false;
        }
        $config = \Yii::$app->params['weixin'];
        RequestService::setConfig($config['appid'], $config['token'], $config['sk']);
        $openId = self::getOpenId($pay_order_info['member_id']);
        if (!$openId) {
            return false;
        }
        $template_id = 'F1uHqtzXWyZ57U7VOtDDH7XQkqZXDiQ697EqcEKtCos';
        $pay_money = $pay_order_info["pay_price"];
        $data = [
            "first" => [
                "value" => "您的订单已经标记发货，请留意查收付成功",
                "color" => "#173177"
            ],
            "keyword1" => [
                "value" => $pay_order_info['note'],
                "color" => "#173177"
            ],
            "keyword2" => [
                "value" => date("Ymd", strtotime($pay_order_info['created_time'])) . $pay_order_info['id'],
                "color" => "#173177"
            ],
            "keyword3" => [
                "value" => date("Y-m-d H:i", strtotime($pay_order_info['pay_time'])),
                "color" => "#173177"
            ],
            "keyword4" => [
                "value" => $pay_money,
                "color" => "#173177"
            ],
            "remark" => [
                "value" => "点击查看详情",
                "color" => "#173177"
            ]
        ];

        return self::send($openId, $template_id, UrlService::buildMUrl('/user/order/'), $data);
    }

    public static function send($openid, $template_id, $url, $data)
    {

        $msg = [
            "touser" => $openid,
            "template_id" => $template_id,
            'url' => $url,
            'data' => $data
        ];
        $token = RequestService::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
        return HttpClient::post($url, json_encode($msg));
    }

    public static function getOpenId($member_id)
    {

        $open_infos = OauthMemberBind::findAll(['member_id' => $member_id, 'type' => 1]);
        if (!$open_infos) {
            return false;
        }
        foreach ($open_infos as $open_info) {
            if (self::getPublicByOpenId($open_info['openid'])) {
                return $open_info['openid'];
            }

        }

        return false;
    }

    private static function getPublicByOpenId($openid)
    {

        $token = RequestService::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
        $info = HttpClient::get($url);

        if (!$info || isset($info['errcode'])) {
            return false;
        }
        $info = json_decode($info,true);
        if ($info['subscribe']) {
            return true;
        }

        return false;
    }
}