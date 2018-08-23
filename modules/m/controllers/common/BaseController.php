<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/13
 * Time: 14:16
 */

namespace app\modules\m\controllers\common;

use app\common\components\BaseWebController;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\models\member\Member;
use http\Url;

class BaseController extends BaseWebController
{

    protected $openid = 'saff';
    protected $auth_cookie_name = 'book_member';
    protected $salt = "dm3HsNYz3Uyddd46Rjg";
    protected $current_user = null;

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';

        $share_info = [
            'title' => \Yii::$app->params['title'],
            'desc' => \Yii::$app->params['desc'],
            'img_url' => UrlService::buildWwwUrl('/images/common/qrcode.jpg')
        ];

        \Yii::$app->view->params['share_info'] = json_encode($share_info);
    }

    protected $allowAllAction = [
        'm/oauth/login',
        'm/oauth/logout',
        'm/oauth/callback',
        'm/user/bind',
        'm/pay/callback',
        'm/product/ops',
        'm/product/search',
    ];

    /**
     * 以下特殊url
     * 如果在微信中,可以不用登录(但是必须要有openid)
     * 如果在H5浏览器,可以不用登录
     */
    public $special_AllowAction = [
        'm/default/index',
        'm/product/index',
        'm/product/info'
    ];

    public function beforeAction($action)
    {
        $login_status = $this->checkLoginStatus();

        if (in_array($action->getUniqueId(), $this->allowAllAction)) {
            return true;
        }
        if (!$login_status) {

            if (\Yii::$app->request->isAjax) {
                $this->renderJSON([], '未登录，系统引导你重新登录!', 302);

            } else {
                if (UtilService::isWechat()) {
                    $openid = $this->getCookie($this->openid);
                    if (!$openid) {
                        if (in_array($action->getUniqueId(), $this->special_AllowAction)) {
                            return true;
                        } else {
                            $this->redirect(UrlService::buildMUrl('/oauth/login'));
                        }
                    }
                } else {

                    if (in_array($action->getUniqueId(), $this->special_AllowAction)) {
                        return true;
                    }

                }

                $redirect_url = UrlService::buildMUrl('/user/bind');

                $this->redirect($redirect_url);
            }

            return false;
        }
        return true;
    }

    public function setLoginStatus($user_info)
    {

        $auth_token = $this->genAuthToken($user_info);

        $this->setCookie($this->auth_cookie_name, $auth_token . '#' . $user_info['id']);
    }

    public function genAuthToken($memeber_info)
    {

        return md5($this->salt . "-{$memeber_info['id']}-{$memeber_info['mobile']}-{$memeber_info['salt']}");

    }


    public function checkLoginStatus()
    {

        $auth_cookie = $this->getCookie($this->auth_cookie_name);

        if (!$auth_cookie) {
            return false;
        }

        list($auth_token, $member_id) = explode('#', $auth_cookie);

        if (!$auth_token || !$member_id) {
            return false;
        }

        if ($member_id && preg_match('/^\d+$/', $member_id)) {

            $member_info = Member::findOne(['id' => $member_id, 'status' => 1]);

            if (!$member_info) {
                $this->removeAuthToken();
                return false;
            }

            if ($auth_token != $this->genAuthToken($member_info)) {
                $this->removeAuthToken();
                return false;
            }

            $this->current_user = $member_info;
            \Yii::$app->view->params['current_user'] = $member_info;
            return true;

        }
        return false;
    }


    public function removeAuthToken()
    {
        $this->removeCookie($this->auth_cookie_name);
    }
}