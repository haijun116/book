<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5
 * Time: 18:14
 */

namespace app\modules\m\controllers;


use app\common\components\HttpClient;
use app\common\services\ConstantMapService;
use app\common\services\UrlService;
use app\models\member\Member;
use app\models\member\OauthMemberBind;
use app\modules\m\controllers\common\BaseController;
use yii\web\Response;

class OauthController extends BaseController
{

    public function actionLogin()
    {

        $scope = $this->get('scope', 'snsapi_base');
        $appid = \Yii::$app->params['weixin']['appid'];
        $redirect_uri = UrlService::buildMUrl('/oauth/callback');
        $url =
            "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state=#wechat_redirect";

        return $this->redirect($url);

    }

    public function actionCallback()
    {


        $code = $this->get('code', '');
        if (!$code) {
            return $this->goHome();
        }

        $appid = \Yii::$app->params['weixin']['appid'];
        $secret = \Yii::$app->params['weixin']['sk'];

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";

        $ret = HttpClient::get($url);

        $ret = @json_decode($ret, true);


        $ret_token = $ret['access_token'];
        //var_dump($ret_token);


        if (!$ret_token) {

            $this->goHome();
        }
        $openid = $ret['openid'];
        $this->setCookie($this->openid, $openid);

        $bind_info = OauthMemberBind::find()->where(['openid' => $openid])->one();
        //var_dump($bind_info);
        if ($bind_info) {
            $member_info = Member::findOne(['id' => $bind_info['member_id'], 'status' => 1]);
            if (!$member_info) {
                $bind_info->delete();
                return $this->goHome();

            }
            if ($ret['scope'] == 'snsapi_userinfo') {
                $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ret_token}&openid={$openid}&lang=zh_CN";
                $wechat_user_info = HttpClient::get($url);
                $wechat_user_info = @json_decode($wechat_user_info, true);
                //var_dump($wechat_user_info);
                if($member_info['nickname'] == $member_info['mobile']){
                    $member_info->nickname =
                        isset($wechat_user_info['nickname']) ? $wechat_user_info['nickname'] : $member_info->nickname;

                    $member_info->update(0);
                }

            }
            //设置登录态

            $this->setLoginStatus($member_info);
        }


        return $this->redirect(UrlService::buildMUrl('/default/index'));
    }

}